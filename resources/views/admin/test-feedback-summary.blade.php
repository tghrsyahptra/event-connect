<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Feedback Summary API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold mb-8 text-center">Test Feedback Summary API</h1>

        <!-- Login Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">1. Login as Organizer</h2>
            <div class="space-y-4">
                <input type="email" id="email" placeholder="Email" value="organizer@example.com" 
                    class="w-full px-4 py-2 border rounded">
                <input type="password" id="password" placeholder="Password" value="password" 
                    class="w-full px-4 py-2 border rounded">
                <button onclick="login()" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Login
                </button>
                <div id="loginResult" class="mt-2"></div>
            </div>
        </div>

        <!-- Event Selection -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">2. Select Event</h2>
            <input type="number" id="eventId" placeholder="Event ID" value="1" 
                class="w-full px-4 py-2 border rounded mb-2">
        </div>

        <!-- Generate Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">3. Generate Summary</h2>
            <button onclick="generateSummary()" 
                class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                Generate AI Summary
            </button>
            <div id="generateResult" class="mt-4"></div>
        </div>

        <!-- Get Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">4. Get Summary</h2>
            <button onclick="getSummary()" 
                class="bg-purple-500 text-white px-6 py-2 rounded hover:bg-purple-600">
                Get Summary
            </button>
            <div id="summaryResult" class="mt-4"></div>
        </div>

        <!-- Get Detailed Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">5. Get Detailed Summary</h2>
            <button onclick="getDetailedSummary()" 
                class="bg-indigo-500 text-white px-6 py-2 rounded hover:bg-indigo-600">
                Get Detailed Summary
            </button>
            <div id="detailedResult" class="mt-4"></div>
        </div>
    </div>

    <script>
        let token = '';
        const apiUrl = '/api';

        async function login() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(`${apiUrl}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
                
                if (data.success) {
                    token = data.data.token;
                    document.getElementById('loginResult').innerHTML = 
                        `<div class="text-green-600">✓ Logged in successfully! Token saved.</div>`;
                } else {
                    document.getElementById('loginResult').innerHTML = 
                        `<div class="text-red-600">✗ ${data.message}</div>`;
                }
            } catch (error) {
                document.getElementById('loginResult').innerHTML = 
                    `<div class="text-red-600">✗ Error: ${error.message}</div>`;
            }
        }

        async function generateSummary() {
            const eventId = document.getElementById('eventId').value;

            if (!token) {
                alert('Please login first!');
                return;
            }

            document.getElementById('generateResult').innerHTML = 
                '<div class="text-blue-600">Generating summary... Please wait.</div>';

            try {
                const response = await fetch(`${apiUrl}/events/${eventId}/feedback/generate-summary`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('generateResult').innerHTML = `
                        <div class="bg-green-50 p-4 rounded border border-green-200">
                            <div class="text-green-800 font-semibold mb-2">✓ ${data.message}</div>
                            <div class="text-sm text-gray-700 mb-2">
                                <strong>Feedback Count:</strong> ${data.data.feedback_count}<br>
                                <strong>Average Rating:</strong> ${data.data.average_rating}/5
                            </div>
                            <div class="bg-white p-3 rounded border mt-2">
                                <strong>Summary:</strong><br>
                                ${data.data.summary}
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('generateResult').innerHTML = 
                        `<div class="text-red-600">✗ ${data.message}</div>`;
                }
            } catch (error) {
                document.getElementById('generateResult').innerHTML = 
                    `<div class="text-red-600">✗ Error: ${error.message}</div>`;
            }
        }

        async function getSummary() {
            const eventId = document.getElementById('eventId').value;

            if (!token) {
                alert('Please login first!');
                return;
            }

            try {
                const response = await fetch(`${apiUrl}/events/${eventId}/feedback/summary`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    const ratings = data.data.rating_distribution;
                    document.getElementById('summaryResult').innerHTML = `
                        <div class="bg-blue-50 p-4 rounded border border-blue-200">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <strong>Total Feedbacks:</strong> ${data.data.current_feedback_count}<br>
                                    <strong>Average Rating:</strong> ${data.data.average_rating}/5<br>
                                    <strong>Generated:</strong> ${new Date(data.data.generated_at).toLocaleString()}
                                </div>
                                <div>
                                    <strong>Rating Distribution:</strong><br>
                                    5⭐: ${ratings['5_star']}<br>
                                    4⭐: ${ratings['4_star']}<br>
                                    3⭐: ${ratings['3_star']}<br>
                                    2⭐: ${ratings['2_star']}<br>
                                    1⭐: ${ratings['1_star']}
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded border">
                                <strong>Summary:</strong><br>
                                ${data.data.summary}
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('summaryResult').innerHTML = 
                        `<div class="text-red-600">✗ ${data.message}</div>`;
                }
            } catch (error) {
                document.getElementById('summaryResult').innerHTML = 
                    `<div class="text-red-600">✗ Error: ${error.message}</div>`;
            }
        }

        async function getDetailedSummary() {
            const eventId = document.getElementById('eventId').value;

            if (!token) {
                alert('Please login first!');
                return;
            }

            try {
                const response = await fetch(`${apiUrl}/events/${eventId}/feedback/summary/detailed`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    const stats = data.data.statistics;
                    const feedbacks = data.data.feedbacks;
                    
                    let feedbackHtml = feedbacks.map(f => `
                        <div class="border-b pb-2 mb-2">
                            <div class="flex justify-between">
                                <strong>${f.user.name}</strong>
                                <span>${'⭐'.repeat(f.rating)}</span>
                            </div>
                            <p class="text-sm text-gray-600">${f.comment}</p>
                            <p class="text-xs text-gray-400 mt-1">${new Date(f.created_at).toLocaleString()}</p>
                        </div>
                    `).join('');
                    
                    document.getElementById('detailedResult').innerHTML = `
                        <div class="bg-indigo-50 p-4 rounded border border-indigo-200">
                            <div class="mb-4">
                                <strong>Summary:</strong><br>
                                <div class="bg-white p-3 rounded border mt-2">
                                    ${data.data.summary || 'No summary available'}
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <strong>Statistics:</strong><br>
                                Total: ${stats.total_feedbacks} | Average: ${stats.average_rating}/5
                            </div>
                            
                            <div>
                                <strong>All Feedbacks:</strong>
                                <div class="mt-2 max-h-96 overflow-y-auto">
                                    ${feedbackHtml}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('detailedResult').innerHTML = 
                        `<div class="text-red-600">✗ ${data.message}</div>`;
                }
            } catch (error) {
                document.getElementById('detailedResult').innerHTML = 
                    `<div class="text-red-600">✗ Error: ${error.message}</div>`;
            }
        }
    </script>
</body>
</html>