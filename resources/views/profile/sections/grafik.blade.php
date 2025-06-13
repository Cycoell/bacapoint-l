<div>
    <h2 class="text-2xl font-bold mb-4">Grafik Genre Buku</h2>
    <div class="bg-white p-4 rounded-lg shadow">
        <canvas id="genreChart" width="400" height="200"></canvas>
    </div>
    <script>
        // Placeholder untuk data genre
        const ctx = document.getElementById('genreChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Fiction', 'Non-Fiction', 'Education', 'Technology', 'Others'],
                datasets: [{
                    label: 'Jumlah Buku per Genre',
                    data: [12, 19, 3, 5, 2],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
