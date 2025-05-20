document.addEventListener("DOMContentLoaded", () => {
    // Données factices
    const userData = [5, 12, 8, 16, 20, 22, 30, 28, 24, 35, 40, 50];
    const reservationData = [2, 6, 4, 10, 12, 18, 25, 20, 15, 22, 27, 32];
    const dailyIncome = [200, 180, 300, 400, 250, 270, 350]; // Derniers 7 jours
  
    // Statistiques
    document.getElementById("total-users").textContent = 354;
    document.getElementById("total-reservations").textContent = 218;
    document.getElementById("total-properties").textContent = 48;
    document.getElementById("revenue").textContent = "12 450 DT";
  
    // Graphique Utilisateurs
    new Chart(document.getElementById("userChart"), {
      type: 'line',
      data: {
        labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sep", "Oct", "Nov", "Déc"],
        datasets: [{
          label: 'Croissance des utilisateurs',
          data: userData,
          borderColor: '#40916c',
          backgroundColor: 'rgba(64, 145, 108, 0.2)',
          fill: true,
          tension: 0.3
        }]
      }
    });
  
    // Graphique Réservations
    new Chart(document.getElementById("reservationChart"), {
      type: 'bar',
      data: {
        labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sep", "Oct", "Nov", "Déc"],
        datasets: [{
          label: 'Réservations mensuelles',
          data: reservationData,
          backgroundColor: '#2d6a4f'
        }]
      }
    });
  
    // Graphique Revenu quotidien
    new Chart(document.getElementById("dailyIncomeChart"), {
      type: 'line',
      data: {
        labels: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
        datasets: [{
          label: 'Revenu quotidien (DT)',
          data: dailyIncome,
          borderColor: '#1b4332',
          backgroundColor: 'rgba(27, 67, 50, 0.1)',
          fill: true,
          tension: 0.3
        }]
      }
    });
  });
  