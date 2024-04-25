function mobilenav() {
    const nav = document.querySelector("nav")
    if(!nav.classList.contains("open")) {
        nav.classList.toggle("open")
    } else {
        nav.classList.toggle("open")
    }
}

function displayAlert() {
  const alert = document.getElementById("alert")
  alert.style.zIndex = 99
}
function hideAlert() {
  const alert = document.getElementById("alert")
  alert.style.zIndex = -1
}

function subnav(subNavId) {
    const subNav = document.getElementById(subNavId);
    subNav.classList.toggle("subnavopen")
}

const months = Array.from({length: 12}, (e, i) => {
    return new Date(null, i + 1, null).toLocaleDateString("cs", {month: "long"});
 })

const ctx = document.getElementById('myChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: months,
    datasets: [{
      label: 'Prodej',
      data: [12, 19, 3, 5, 2, 3, 8, 45, 78, 34, 23, 7],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});


var ctx2 = document.getElementById('pieChart');
new Chart(ctx2, {
    type: 'pie',
    data: {
      labels: ["Velice spokojený", "Docela spokojený", "Neutrál", "Trochu nespokojený", "Velice nespokojený"],
      datasets: [{
          data: [30, 25, 15, 10, 20], // Příklad hodnot pro každý stupeň spokojenosti (procentuálně)
          backgroundColor: [
              '#4CAF50',
              '#8BC34A',
              '#FFC107',
              '#FF9800',
              '#F44336'
          ]
      }]
    },
    options: {
  }
});

var ctx3 = document.getElementById('nastevnost');
new Chart(ctx3, {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: 'Návštěvnost',
      data: [12, 19, 23, 25, 42, 13, 28, 45, 28, 34, 32, 27],
      borderWidth: 1,
      fill: true,
      tension: 0.4 // Nastavení hladkosti křivky (0 - 1)
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
