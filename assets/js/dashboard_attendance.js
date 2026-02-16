"use strict";

// Graphic peak service
const peak_service = {
    labels: [
        "00:00", "01:00", "02:00", "03:00", "04:00",
        "05:00", "06:00", "07:00", "08:00", "09:00",
        "10:00", "11:00", "12:00", "13:00", "14:00",
        "15:00", "16:00", "17:00", "18:00", "19:00",
        "20:00", "21:00", "22:00", "23:00"
    ],
    datasets: [{
        label: GLOBAL_LANG.dashboard_attendance_graph_peak_service_caption,
        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        tension: 0.4, // Para suavizar as curvas
        fill: true, // Ativa o preenchimento abaixo da linha
        pointRadius: 5, // Ajusta o tamanho dos pontos
        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
        pointBorderColor: 'white',
        pointBorderWidth: 2
    }]
};

const peak_service_config = {
    type: 'line',
    data: peak_service,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false // Remove a legenda
            },
            title: {
                display: true, // Exibe o título do gráfico
                font: {
                    size: 16,
                    family: 'Arial, sans-serif',
                    weight: 'bold'
                },
                color: "#333"
            },
            subtitle: {
                display: true,
                font: {
                    size: 12
                },
                color: "#666"
            },
            tooltip: {
                enabled: true,
                mode: 'index',
                intersect: false
            }
        },
        elements: {
            point: {
                radius: 5,
                backgroundColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                borderColor: 'white'
            }
        },
        scales: {
            x: {
                type: 'category',
                offset: true,
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 12
                    },
                    align: 'center' // Centraliza os rótulos
                }
            },
            y: {
                ticks: {
                    stepSize: 5, // Define os intervalos do eixo Y
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: '#e0e0e0',
                    lineWidth: 1
                }
            }
        }
    }
};

const peak_service_ctx = document.getElementById('graph_peak_service').getContext('2d');
let peak_service_chart = new Chart(peak_service_ctx, peak_service_config);

const started_end_closed = {
    labels: [

    ],
    datasets: [
        {
            label: GLOBAL_LANG.dashboard_attendance_graph_started_end_closed_leng_star,
            data: [],
            backgroundColor: 'rgba(12, 155, 225, 0.7)',
            borderColor: 'rgba(12, 155, 225, 0.7)',
            borderWidth: 1
        },
        {
            label: GLOBAL_LANG.dashboard_attendance_graph_started_end_closed_leng_closed,
            data: [],
            backgroundColor: 'rgba(69, 160, 78, 0.7)',
            borderColor: 'rgba(69, 160, 78, 0.7)',
            borderWidth: 1
        }
    ]
};

const started_end_closed_config = {
    type: 'bar',
    data: started_end_closed,
    options: {
        responsive: true,
        maintainAspectRatio: false, // Se quiser controlar totalmente a altura via CSS
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: { beginAtZero: true },
            x: { beginAtZero: true, suggestedMax: 120, grid: { display: false } }
        }
    }
};

const started_end_closed_ctx = document.getElementById('graph_started_end_closed').getContext('2d');
let started_end_closed_chart = new Chart(started_end_closed_ctx, started_end_closed_config);


const solidColors = [
    '#4285F4', // azul
    '#EA4335', // vermelho
    '#FBBC05', // amarelo
    '#34A853', // verde
    '#AECBFA', // azul claro
    '#FABB05', // laranja/amarelo
    '#00C49F', // verde água
    '#00ACC1', // azul petróleo
    '#FF7043', // laranja queimado
    '#9E9D24', // oliva
    '#F06292', // rosa claro
];

const category_distribution_data = {
    labels: [],
    datasets: [{
        label: 'Distribuição de Categorias',
        data: [],
        backgroundColor: [],
        borderWidth: 0,
        hoverOffset: 10
    }]
};

const category_distribution_config = {
    type: 'doughnut',
    data: category_distribution_data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        layout: {
            padding: 20
        },
        plugins: {
            legend: {
                position: 'left',
                labels: {
                    boxWidth: 10,
                    font: {
                        size: window.innerWidth > 768 ? 12 : 10
                    },
                    generateLabels: function (chart) {
                        const original = Chart.overrides.doughnut.plugins.legend.labels.generateLabels;
                        const labels = original(chart);
                        return labels.slice(0, 16);
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        if (context.raw === 1)
                            return 0;
                        return `${context.label}: ${context.raw}`
                    }
                }
            }
        },
    }
};

const chatbot_quantitative_data = {
    labels: [],
    datasets: [{
        label: 'Quantidade de Chatbots',
        data: [],
        backgroundColor: [],
        borderWidth: 1
    }]
};

const chatbot_quantitative_config = {
    type: 'bar',
    data: chatbot_quantitative_data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: 20
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    title: function () {
                        return '';
                    },
                    label: function (context) {
                        const originalLabel = context.dataset.originalLabels[context.dataIndex];
                        const value = context.parsed.y;
                        return `${originalLabel}: ${value}`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
};

let attendance_origin_data = {
    labels: [],
    datasets: [{
        data: [],
        backgroundColor: []
    }]
};

const attendance_origin_config = {
    type: 'pie',
    data: attendance_origin_data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        if (label === NO_DATA_LABEL) {
                            return NO_DATA_LABEL;
                        }
                        return `${label}: ${value}%`;
                    }
                }
            }
        },
    },
};

let abandonment_chart;
const abandonment_data = {
    labels: [],
    datasets: [{
        data: [],
        backgroundColor: []
    }]
};
const abandonment_config = {
    type: 'bar',
    data: abandonment_data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        return `${context.parsed.y}%`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function (value) {
                        return value + '%';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
};

let attendance_origin_chart;


let category_distribution_chart = null;

let chatbot_quantitative_chart = null;

let allUsers = [];

let usersPerPage = 10;

let currentPage = 1;

const NO_DATA_LABEL = GLOBAL_LANG.dashboard_attendance_graph_chatbot_origin_no_data;

async function getAvgWaitTime(sector, channel, period) {

    try {
        const formData = new FormData();

        formData.append('sector', sector);
        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getAvgWaitTime`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            document.getElementById("avgWaitTime").innerText = data;
            hideLoading("loadingAvgWaitTime", "avgWaitTime");
            console.log(data, "getAvgWaitTime");

        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getAvgResponseTime(sector, channel, period) {

    try {
        const formData = new FormData();

        formData.append('sector', sector);
        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getAvgResponseTime`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            document.getElementById("avgResponseTime").innerText = data;

            let hour = parseInt(data.split('h')[0]);
            let elem = document.getElementById("avgResponseTime");

            if (hour >= 2) {
                elem.classList.add('text-danger');
            } else {
                elem.classList.remove('text-danger');
            }

            hideLoading("loadingAvgResponseTime", "avgResponseTime");
            console.log(data, "getAvgResponseTime");

        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getAvgServiceTime(sector, channel, period) {

    try {
        const formData = new FormData();

        formData.append('sector', sector);
        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getAvgServiceTime`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            document.getElementById("avgServiceTime").innerText = data;
            hideLoading("loadingAvgServiceTime", "avgServiceTime");
            console.log(data, "getAvgServiceTime");

        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getTotalAttendances(sector, channel, period) {

    try {
        const formData = new FormData();

        formData.append('sector', sector);
        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getTotalAttendances`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            document.getElementById("totalAttendances").innerText = data["count_started_chats"];
            hideLoading("loadingTotalAttendances", "totalAttendances");
            console.log(data, "getTotalAttendances");

        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getPeakService(sector, channel, period) {

    try {
        const formData = new FormData();

        formData.append('sector', sector);
        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getPeakService`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            console.log(data, "GetPeakService");

            peak_service_chart.data.datasets[0].data = data;
            peak_service_chart.update();
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getStartedEndClosed(sector, channel, period) {

    try {
        const formData = new FormData();

        formData.append('sector', sector);
        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getStartedEndClosed`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            const data_arr = data;

            if (data_arr != null && Array.isArray(data_arr)) {
                if (data_arr.length > 0) {

                    started_end_closed.labels = data_arr.map(item => item.attendance_date);
                    started_end_closed.datasets[0].data = data_arr.map(item => item.start_count);
                    started_end_closed.datasets[1].data = data_arr.map(item => item.end_count);

                    started_end_closed_chart.update();
                } else {
                    started_end_closed.labels = [];
                    started_end_closed.datasets[0].data = [];
                    started_end_closed.datasets[1].data = [];

                    started_end_closed_chart.update();
                }
            }

        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getCategoryDistribution(sector, channel, period) {
    try {
        const url = new URL(`${document.location.origin}/dashboard/attendance/getCategoryDistribution`);
        if (sector) url.searchParams.append('sector', sector);
        if (channel) url.searchParams.append('id_channel', channel);
        if (period) url.searchParams.append('period', period);

        const response = await fetch(url.toString(), {
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': Cookies.get("csrf_cookie_talkall") || ''
            }
        });

        if (response.ok) {
            const result = await response.json();
            const data_arr = result.data;

            const canvas = document.getElementById('categoryDistributionChart').getContext('2d');
            const no_data_div = document.getElementById('categoryNoDataMessage');

            document.getElementById('categoryDistributionChart').style.display = 'block';
            no_data_div.style.display = 'none';
            document.getElementById('categoryNoDataMessage').style.display = 'none';

            const labels = Array.isArray(data_arr) && data_arr.length > 0
                ? data_arr.map(item => item.category_name)
                : [`${GLOBAL_LANG.dashboard_attendance_graph_category_no_data}`];

            const values = Array.isArray(data_arr) && data_arr.length > 0
                ? data_arr.map(item => item.total)
                : [1];

            const bgColors = Array.isArray(data_arr) && data_arr.length > 0
                ? getColors(data_arr.length)
                : ['#e0e0e0'];

            category_distribution_data.labels = labels;
            category_distribution_data.datasets[0].data = values;
            category_distribution_data.datasets[0].backgroundColor = bgColors;

            if (category_distribution_chart) category_distribution_chart.destroy();
            category_distribution_chart = new Chart(canvas, category_distribution_config);

        } else {
            console.error('Erro na requisição GET:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getChatbotQuantitative(channel, period) {
    try {
        const formData = new FormData();
        if (channel) formData.append('id_channel', channel);
        if (period) formData.append('period', period);

        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getChatbotQuantitative`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const result = await response.json();
            const data_arr = result.data;

            if (Array.isArray(data_arr) && data_arr.length > 0) {
                const originalLabels = data_arr.map(item => {
                    if (item.option_name === 'Main_menu') {
                        return GLOBAL_LANG.dashboard_attendance_graph_chatbot_quantitative_main_menu;
                    } else {
                        return item.option_name || 'Sem opção';
                    }
                });

                chatbot_quantitative_data.labels = originalLabels.map(label => {
                    return label.length > 10 ? label.substring(0, 10) + '...' : label;
                });

                chatbot_quantitative_data.datasets[0].data = data_arr.map(item => item.total);
                chatbot_quantitative_data.datasets[0].backgroundColor = getColors(data_arr.length);

                chatbot_quantitative_data.datasets[0].originalLabels = originalLabels;
            } else {
                chatbot_quantitative_data.labels = [];
                chatbot_quantitative_data.datasets[0].data = [];
                chatbot_quantitative_data.datasets[0].backgroundColor = [];
                chatbot_quantitative_data.datasets[0].originalLabels = [];
            }

            const canvas = document.getElementById('graph_chatbot_quantitative').getContext('2d');

            if (chatbot_quantitative_chart) chatbot_quantitative_chart.destroy();
            chatbot_quantitative_chart = new Chart(canvas, chatbot_quantitative_config);

        } else {
            console.error('Erro na requisição POST:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getAttendanceOrigin(sector, channel, period) {
    try {
        const url = new URL(`${document.location.origin}/dashboard/attendance/getAttendanceOrigin`);
        if (sector) url.searchParams.append('sector', sector);
        if (channel) url.searchParams.append('id_channel', channel);
        if (period) url.searchParams.append('period', period);

        const response = await fetch(url.toString(), {
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': Cookies.get("csrf_cookie_talkall") || ''
            }
        });

        if (response.ok) {
            const result = await response.json();
            const data_arr = result.data;

            const canvasEl = document.getElementById('graph_attendance_origin');
            const ctx = canvasEl.getContext('2d');

            if (attendance_origin_chart) {
                attendance_origin_chart.destroy();
                attendance_origin_chart = null;
            }

            if (Array.isArray(data_arr) && data_arr.length > 0) {
                const total = data_arr.reduce((sum, item) => sum + parseInt(item.total), 0);
                const labels = data_arr.map(item => {
                    if (item.start_mode == 1) return GLOBAL_LANG.dashboard_attendance_graph_chatbot_origin_manual;
                    if (item.start_mode == 2) return GLOBAL_LANG.dashboard_attendance_graph_chatbot_origin_organic;
                });
                const values = data_arr.map(item => {
                    const percent = (parseInt(item.total) / total) * 100;
                    return Math.round(percent);
                });
                const bgColors = getColorPadrao(data_arr.length);

                attendance_origin_data.labels = labels;
                attendance_origin_data.datasets[0].data = values;
                attendance_origin_data.datasets[0].backgroundColor = bgColors;

            } else {
                attendance_origin_data.labels = [NO_DATA_LABEL];
                attendance_origin_data.datasets[0].data = [100];
                attendance_origin_data.datasets[0].backgroundColor = ['#e0e0e0'];
            }

            attendance_origin_chart = new Chart(ctx, attendance_origin_config);

        } else {
            console.error('Erro na requisição GET:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getChatbotAbandonment(channel, period) {
    try {
        const formData = new FormData();
        if (channel) formData.append('id_channel', channel);
        if (period) formData.append('period', period);

        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/attendance/getChatbotAbandonment`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const result = await response.json();
            const data_arr = result.data;

            if (Array.isArray(data_arr) && data_arr.length > 0) {
                // Pega os valores fixos
                const abandono = data_arr.find(item => item.is_automatic_transfer == "1");
                const semAbandono = data_arr.find(item => item.is_automatic_transfer == "2");

                const abandonoTotal = abandono ? parseInt(abandono.total) : 0;
                const semAbandonoTotal = semAbandono ? parseInt(semAbandono.total) : 0;

                const total = abandonoTotal + semAbandonoTotal;

                const abandonoPerc = total > 0 ? ((abandonoTotal / total) * 100).toFixed(2) : 0;
                const semAbandonoPerc = total > 0 ? ((semAbandonoTotal / total) * 100).toFixed(2) : 0;

                abandonment_data.labels = [
                    GLOBAL_LANG.dashboard_attendance_graph_abandonment,
                    GLOBAL_LANG.dashboard_attendance_graph_no_abandonment
                ];
                abandonment_data.datasets[0].data = [abandonoPerc, semAbandonoPerc];
                abandonment_data.datasets[0].backgroundColor = getColorPadrao(2);
            } else {
                abandonment_data.labels = [];
                abandonment_data.datasets[0].data = [];
                abandonment_data.datasets[0].backgroundColor = [];
            }

            const canvas = document.getElementById('graph_chatbot_abandonment').getContext('2d');

            if (abandonment_chart) abandonment_chart.destroy();
            abandonment_chart = new Chart(canvas, abandonment_config);

        } else {
            console.error('Erro na requisição POST:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function GetUserStatus(sector, channel, period) {
    let dataTable;

    function initDataTable() {
        dataTable = $('#datatable-basic').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/dashboard/attendance/getUserStatus",
                type: "POST",
                data: function (d) {
                    return {
                        text: $("#search-input").val(),
                        sector: sector,
                        id_channel: channel,
                        period: period,
                        csrf_talkall: Cookies.get("csrf_cookie_talkall"),
                        draw: d.draw,
                        start: d.start,
                        length: d.length,
                        order: d.order,
                        columns: d.columns
                    };
                }
            },
            columns: [
                { mData: 'name' },      
                { mData: 'started' },   
                { mData: 'in_progress' },
                { mData: 'on_hold' },   
                { mData: 'finished' },  
                { mData: 'tma' },       
                { mData: 'tmr' },       
                { mData: 'rating' }     
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: true,
                    render: function (data, type, full) {
                        return `
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">
                                    <img src="https://files.talkall.com.br:3000/p/${full.user_key_remote_id}.jpeg"
                                         class="avatar rounded-circle mr-3"
                                         alt="photo">
                                </div>
                                <div class="kt-user-card-v2__details">
                                    <b>${full.name}</b>
                                </div>
                            </div>`;
                    }
                },
                {
                    targets: [1, 2, 3, 4],
                    className: 'text-center',
                    orderable: true
                },
                {
                    targets: [5, 6],
                    className: 'text-center',
                    orderable: false
                },
                {
                    targets: 7,
                    className: 'text-center',
                    orderable: true,
                    orderData: [7],
                    render: function (data, type) {

                        const ratingValue = Number(data);

                        // sem avaliação
                        if (!ratingValue || ratingValue <= 0) {

                            if (type === 'sort') return -1;

                            return `<div>
                                        <span style="font-size:12px;color:#999;">—</span>
                                    </div>`;
                        }

                        const rating = Math.round(ratingValue);
                        let stars = '';

                        for (let i = 1; i <= 5; i++) {
                            stars += i <= rating
                                ? `<span style="color:#ffc107;font-size:16px;">★</span>`
                                : `<span style="color:#e0e0e0;font-size:16px;">☆</span>`;
                        }

                        return `<div data-rating="${rating}">
                                ${stars}
                                <span style="font-size:12px;color:#001cb8;">(${rating}/5)</span>
                        </div>`;
                    }
                }

            ],
            pagingType: "numbers",
            pageLength: 5,
            destroy: true,
            ordering: true,
            order: [[0, 'asc']],
            fixedHeader: true,
            responsive: true,
            lengthChange: false,
            searching: false,
            paginate: true,
            info: true,
            language: {
                url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
            }
        });
    }

    $(document).ready(function () {
        initDataTable();

        $('#search-input').on('keypress', function (e) {
            if (e.which === 13) dataTable.ajax.reload();
        });

        $('#search-input').on('keyup', function () {
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                dataTable.ajax.reload();
            }, 1000);
        });
    });
}

function renderUsers() {
    const tbody = document.getElementById('attendance-tbody');
    const searchValue = document.getElementById('search-user')?.value.toLowerCase().trim() || '';
    const loadMoreContainer = document.getElementById('load-more-container');

    let filtered = allUsers;

    if (searchValue) {
        filtered = allUsers.filter(user =>
            user.name.toLowerCase().includes(searchValue)
        );
    }

    const start = 0;
    const end = currentPage * usersPerPage;
    const usersToShow = filtered.slice(start, end);

    tbody.innerHTML = '';

    usersToShow.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center; vertical-align: middle;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <img class="user-photo" src="https://files.talkall.com.br:3000/p/${user.user_key_remote_id}.jpeg" alt="Foto de ${user.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    <span>${user.name}</span>
                </div>
            </td>
            <td style="text-align: center; vertical-align: middle;">${user.started ?? 0}</td>
            <td style="text-align: center; vertical-align: middle;">${user.in_progress ?? 0}</td>
            <td style="text-align: center; vertical-align: middle;">${user.on_hold ?? 0}</td>
            <td style="text-align: center; vertical-align: middle;">${user.finished ?? 0}</td>
            <td style="text-align: center; vertical-align: middle;">${user.tma ?? '00:00:00'}</td>
            <td style="text-align: center; vertical-align: middle;">${user.tmr ?? '00:00:00'}</td>
        `;
        tbody.appendChild(tr);
    });

    loadMoreContainer.style.display = end < filtered.length ? 'block' : 'none';
}

function getColors(count) {
    const usedColors = new Set();

    const shuffled = [...solidColors].sort(() => 0.5 - Math.random());
    const baseColors = shuffled.slice(0, Math.min(count, shuffled.length));
    baseColors.forEach(color => usedColors.add(color));

    while (baseColors.length < count) {
        const h = Math.floor(Math.random() * 360);
        const color = `hsl(${h}, 65%, 55%)`;
        if (!usedColors.has(color)) {
            baseColors.push(color);
            usedColors.add(color);
        }
    }

    return baseColors;
}

function getColorPadrao(count) {
    const solidColors = [
        '#4e73df',
        '#36b9cc',
        '#e83e8c'
    ];

    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(solidColors[i % solidColors.length]);
    }
    return colors;
}

function showLoading(idLoading, idValue) {
    document.getElementById(idLoading).style.display = '';
    document.getElementById(idValue).style.display = 'none';
}

function hideLoading(idLoading, idValue) {
    document.getElementById(idLoading).style.display = 'none';
    document.getElementById(idValue).style.display = '';
}

function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function loadDashboard(sector, channel, period) {
    showLoading("loadingAvgWaitTime", "avgWaitTime");
    showLoading("loadingAvgResponseTime", "avgResponseTime");
    showLoading("loadingAvgServiceTime", "avgServiceTime");
    showLoading("loadingTotalAttendances", "totalAttendances");

    await getAvgWaitTime(sector, channel, period);
    await delay(10);

    await getAvgResponseTime(sector, channel, period);
    await delay(50);

    await getAvgServiceTime(sector, channel, period);
    await delay(100);

    await getTotalAttendances(sector, channel, period);
    await delay(100);

    await getChatbotAbandonment(channel, period);
    await delay(10);

    await getAttendanceOrigin(sector, channel, period);
    await delay(100);

    await getChatbotQuantitative(channel, period);
    await delay(150);

    await getStartedEndClosed(sector, channel, period);
    await delay(150);

    await getCategoryDistribution(sector, channel, period);
    await delay(250);

    await getPeakService(sector, channel, period);
    await delay(300);

    await GetUserStatus(sector, channel, period);
}

async function searchForBroadcast() {

    const sector = document.getElementById("selectattendanceSector").value;
    const channel = document.getElementById("selectattendanceChannel").value;
    const period = document.getElementById("selectattendancePeriod").value;

    loadDashboard(sector, channel, period);
}

document.addEventListener('DOMContentLoaded', function () {

    new SlimSelect({
        select: '#selectattendanceSector',
        settings: {
            searchPlaceholder: GLOBAL_LANG.dashboard_attendance_filter_search,
            searchText: GLOBAL_LANG.dashboard_attendance_filter_not_found,
            searchHighlight: true,
            noResultsText: GLOBAL_LANG.dashboard_attendance_filter_not_found,
        }
    });

    new SlimSelect({
        select: '#selectattendanceChannel',
        settings: {
            searchPlaceholder: GLOBAL_LANG.dashboard_attendance_filter_search,
            searchText: GLOBAL_LANG.dashboard_attendance_filter_not_found,
            searchHighlight: true,
            noResultsText: GLOBAL_LANG.dashboard_attendance_filter_not_found,
        }
    });

    document.querySelectorAll('.start-slim').forEach(el => el.classList.remove('start-slim'));

    const sector = document.getElementById("selectattendanceSector").value;
    const channel = document.getElementById("selectattendanceChannel").value;
    const period = document.getElementById("selectattendancePeriod").value;

    loadDashboard(sector, channel, period);

    document.getElementById("selectattendanceSector").addEventListener("change", searchForBroadcast);
    document.getElementById("selectattendanceChannel").addEventListener("change", searchForBroadcast);
    document.getElementById("selectattendancePeriod").addEventListener("change", searchForBroadcast);

    window.addEventListener('resize', () => window.location.reload(), true);
});