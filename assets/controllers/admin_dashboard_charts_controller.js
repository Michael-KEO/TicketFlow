import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

/*
 * Stimulus controller to initialize Chart.js charts on the admin dashboard.
 * Data for the charts is expected in data attributes on the controller element.
 */
export default class extends Controller {
    connect() {
        console.log('Admin Dashboard Charts controller connected');
        this.initCharts();
    }

    initCharts() {
        // Data is expected in data attributes, e.g., data-admin-dashboard-charts-tickets-by-status-value
        // We need to parse the JSON data from these attributes.
        const ticketsByStatusData = this.data.get('ticketsByStatus') ? JSON.parse(this.data.get('ticketsByStatus')) : [];
        const ticketsByDevData = this.data.get('ticketsByDev') ? JSON.parse(this.data.get('ticketsByDev')) : [];
        const ticketsEvolutionData = this.data.get('ticketsEvolution') ? JSON.parse(this.data.get('ticketsEvolution')) : [];

        // Graphique des tickets par statut
        const statusCtx = document.getElementById('ticketsByStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ticketsByStatusData.map(item => item.statutTicket ?? "Non défini"),
                    datasets: [{
                        data: ticketsByStatusData.map(item => item.count),
                        backgroundColor: [
                            '#4CAF50', '#FFC107', '#F44336', '#2196F3', '#9C27B0', '#607D8B'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        // Graphique des tickets par développeur
        const devCtx = document.getElementById('ticketsByDevChart');
         if (devCtx) {
            new Chart(devCtx, {
                type: 'bar',
                data: {
                    labels: ticketsByDevData.map(item => `${item.prenom} ${item.nom}`),
                    datasets: [{
                        label: 'Nombre de tickets assignés',
                        data: ticketsByDevData.map(item => item.count),
                        backgroundColor: '#2196F3'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // Graphique d'évolution des tickets
        const evolutionCtx = document.getElementById('ticketsEvolutionChart');
        if (evolutionCtx) {
            new Chart(evolutionCtx, {
                type: 'line',
                data: {
                    labels: ticketsEvolutionData.map(item => `${item.month}/${item.year}`),
                    datasets: [{
                        label: 'Tickets créés',
                        data: ticketsEvolutionData.map(item => item.count),
                        borderColor: '#4CAF50',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    }

    // Optional: disconnect to destroy charts if needed (e.g., for complex scenarios)
    // disconnect() {
    //     // Destroy charts if they exist
    //     if (this.statusChart) this.statusChart.destroy();
    //     if (this.devChart) this.devChart.destroy();
    //     if (this.evolutionChart) this.evolutionChart.destroy();
    // }
} 