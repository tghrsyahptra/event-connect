<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Notification;
use App\Mail\EventReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for events happening tomorrow (H-1)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send event reminders...');

        // Get events starting tomorrow (H-1)
        $tomorrow = Carbon::tomorrow()->startOfDay();
        $tomorrowEnd = Carbon::tomorrow()->endOfDay();

        $events = Event::where('start_date', '>=', $tomorrow)
            ->where('start_date', '<=', $tomorrowEnd)
            ->where('status', 'published')
            ->where('is_active', true)
            ->get();

        if ($events->isEmpty()) {
            $this->info('No events scheduled for tomorrow.');
            return 0;
        }

        $this->info("Found {$events->count()} event(s) for tomorrow.");

        $totalSent = 0;
        $totalFailed = 0;

        foreach ($events as $event) {
            $this->info("Processing event: {$event->title}");

            // Get all confirmed participants for this event
            $participants = EventParticipant::where('event_id', $event->id)
                ->whereIn('status', ['registered'])
                ->with('user')
                ->get();

            if ($participants->isEmpty()) {
                $this->warn("  - No participants found for this event");
                continue;
            }

            foreach ($participants as $participant) {
                try {
                    // Check if reminder already sent
                    $existingNotification = Notification::where('user_id', $participant->user_id)
                        ->where('event_id', $event->id)
                        ->where('type', 'event_reminder_h1')
                        ->whereDate('created_at', Carbon::today())
                        ->first();

                    if ($existingNotification) {
                        $this->warn("  - Reminder already sent to {$participant->user->email}");
                        continue;
                    }

                    // Send email
                    Mail::to($participant->user->email)
                        ->send(new EventReminderMail($event, $participant->user));

                    // Create notification record
                    Notification::create([
                        'user_id' => $participant->user_id,
                        'event_id' => $event->id,
                        'type' => 'event_reminder_h1',
                        'title' => 'Event Reminder: Tomorrow',
                        'message' => "Reminder: {$event->title} is happening tomorrow at {$event->start_date->format('H:i')}",
                        'is_read' => false,
                        'data' => [
                            'event_title' => $event->title,
                            'start_date' => $event->start_date->toDateTimeString(),
                            'end_date' => $event->end_date->toDateTimeString(),
                            'location' => $event->location,
                            'event_type' => $event->event_type,
                            'contact_info' => $event->contact_info,
                        ],
                    ]);

                    $totalSent++;
                    $this->info("  ✓ Reminder sent to {$participant->user->email}");

                } catch (\Exception $e) {
                    $totalFailed++;
                    $this->error("  ✗ Failed to send reminder to {$participant->user->email}: {$e->getMessage()}");
                }
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Total emails sent: {$totalSent}");
        
        if ($totalFailed > 0) {
            $this->error("Total failed: {$totalFailed}");
        }

        $this->info('Event reminders completed!');
        
        return 0;
    }
}