export default {
  responsive: true,
  maintainAspectRatio: false,
  aspectRatio: 1,
  scales: {
      y: {
          title: {
              display: true,
              text: '',
              font: {
                  weight: "bold"
              }
          },
          grid: {
              borderDash: [4, 4],
              color: "rgba(0, 0, 0, 0.05)"
          },
          beginAtZero: true
      },
      x: {
          title: {
              display: true,
              text: '',
              font: {
                  weight: "bold"
              }
          },
          grid: {
              display: false
          }
      }
  },
  plugins: {
      legend: {
          display: false
      }
  }
}