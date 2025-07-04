import React from 'react';
import ReactApexChart from 'react-apexcharts';

const LineChart = () => {
  const series = [
    {
      name: 'Target',
      data: [180, 190, 170, 160, 175, 165, 170, 200, 220, 205, 230, 225],
    },
    {
      name: 'Progress',
      data: [40, 30, 50, 42, 55, 43, 70, 100, 110, 120, 145, 135],
    },
  ];

  const options: ApexCharts.ApexOptions = {
    chart: {
      height: 300,
      type: 'area',
      toolbar: { show: false },
    },
    colors: ['#3B82F6', '#60A5FA'],
    dataLabels: { enabled: false },
    stroke: {
      curve: 'smooth',
      width: 2,
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0,
        stops: [0, 100],
      },
    },
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      labels: {
        style: {
          colors: '#4B5563',
        },
      },
    },
    yaxis: {
      labels: {
        style: {
          colors: '#4B5563',
        },
      },
    },
    legend: {
      show: false,
    },
    grid: {
      borderColor: '#E5E7EB',
    },
  };

  return (
    <ReactApexChart options={options} series={series} type="area" height={300} />
  );
};

export default LineChart;
