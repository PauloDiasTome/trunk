"use strict";

let fixedChannel = true;
let fixedbroadcast = false;

//* Gráfico Campanhas Broadcast //
const broadcast = {
    labels: [
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_monday,
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_tuesday,
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_wednesday,
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_thursday,
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_friday,
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_saturday,
        GLOBAL_LANG.dashboard_broadcast_graph_weekday_sunday,
    ],
    datasets: [{
        label: GLOBAL_LANG.dashboard_broadcast_graph_send,
        data: [120, 150, 180, 170, 200, 90, 100],
        backgroundColor: 'rgba(28, 98, 211, 0.7)',
        borderColor: 'rgba(28, 98, 211, 0.7)',
        borderWidth: 1
    },
    {
        label: GLOBAL_LANG.dashboard_broadcast_graph_received,
        data: [100, 140, 160, 150, 180, 80, 90],
        backgroundColor: 'rgba(211, 28, 98, 0.7)',
        borderColor: 'rgba(211, 28, 98, 0.7)',
        borderWidth: 1
    },
    {
        label: GLOBAL_LANG.dashboard_broadcast_graph_read,
        data: [90, 130, 150, 140, 170, 70, 85],
        backgroundColor: 'rgba(64, 159, 159, 0.7)',
        borderColor: 'rgba(64, 159, 159, 0.7)',
        borderWidth: 1
    }
    ]
};

const broadcast_config = {
    type: 'bar', // Tipo de gráfico: barra
    data: broadcast,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top', // Posição da legenda
            },
        },
        scales: {
            y: {
                beginAtZero: true, // Eixo Y começa no zero
            },
            x: {
                beginAtZero: true, // Eixo Y começa no zero
                suggestedMax: 120, // Limite máximo sugerido
                grid: {
                    display: false, // Remove as linhas de grade do eixo Y (vertical)
                }
            }
        }
    }
};
const broadcast_ctx = document.getElementById('graph_broadcast').getContext('2d');
let broadcast_chart = new Chart(broadcast_ctx, broadcast_config);


//* Gráfico Campanhas Interaction //
const interaction = {
    labels: [
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_monday,
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_tuesday,
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_wednesday,
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_thursday,
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_friday,
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_saturday,
        GLOBAL_LANG.dashboard_broadcast_graph_interaction_weekday_sunday,
    ],
    datasets: [{
        label: GLOBAL_LANG.dashboard_broadcast_graph_interaction_contact,
        data: [120, 150, 180, 170, 200, 90, 100],
        backgroundColor: 'rgba(28, 98, 211, 0.7)',
        borderColor: 'rgba(28, 98, 211, 0.7)',
        borderWidth: 1
    },
    {
        label: GLOBAL_LANG.dashboard_broadcast_graph_interaction_active,
        data: [100, 140, 160, 150, 180, 80, 90],
        backgroundColor: 'rgba(64, 159, 159, 0.7)',
        borderColor: 'rgba(64, 159, 159, 0.7)',
        borderWidth: 1
    },
    {
        label: GLOBAL_LANG.dashboard_broadcast_graph_interaction_inactive,
        data: [90, 130, 150, 140, 170, 70, 85],
        backgroundColor: 'rgba(211, 28, 98, 0.7)',
        borderColor: 'rgba(211, 28, 98, 0.7)',
        borderWidth: 1
    }
    ]
};

const interaction_config = {
    type: 'bar', // Tipo de gráfico: barra
    data: interaction,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top', // Posição da legenda
            },
        },
        scales: {
            y: {
                beginAtZero: true // Eixo Y começa no zero
            },
            x: {
                beginAtZero: true, // Eixo Y começa no zero
                suggestedMax: 120, // Limite máximo sugerido
                grid: {
                    display: false, // Remove as linhas de grade do eixo Y (vertical)
                }
            }
        }
    }
};
const interaction_ctx = document.getElementById('graph_interaction').getContext('2d');
let interaction_chart = new Chart(interaction_ctx, interaction_config);


//* Gráfico de reaction
// const reaction = {
//     labels: [
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_monday,
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_tuesday,
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_wednesday,
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_thursday,
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_friday,
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_saturday,
//         GLOBAL_LANG.dashboard_broadcast_graph_reaction_weekday_sunday,
//     ],
//     datasets: [{
//         label: "",
//         data: [4, 20, 40, 60, 40, 90, 100],
//         borderColor: 'rgba(0, 123, 255, 1)',
//         backgroundColor: 'transparent',
//         tension: 0.4,
//         fill: false,
//         pointRadius: 0
//     }]
// };

// const reaction_config = {
//     type: 'line',
//     data: reaction,
//     options: {
//         responsive: true,
//         plugins: {
//             legend: {
//                 display: false // Remove a legenda
//             },
//             title: {
//                 display: false // Remove o título
//             }
//         },
//         scales: {
//             x: {
//                 beginAtZero: true, // Eixo Y começa no zero
//                 suggestedMax: 120, // Limite máximo sugerido
//                 grid: {
//                     display: false, // Remove as linhas de grade do eixo Y (vertical)
//                 }
//             },
//             y: {
//                 grid: {
//                     display: true, // Exibe as linhas de grade do eixo X (horizontal)
//                     color: '#e0e0e0', // Cor das linhas de grade
//                     lineWidth: 1, // Espessura das linhas
//                 }
//             }
//         }
//     }
// };
// const reaction_ctx = document.getElementById('graph_reaction').getContext('2d');
// const reaction_chart = new Chart(reaction_ctx, reaction_config);


//* Gráfico de active
const active = {
    labels: [
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_monday,
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_tuesday,
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_wednesday,
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_thursday,
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_friday,
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_saturday,
        GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_sunday,
    ],
    datasets: [{
        label: "",
        data: [4, 20, 40, 60, 40, 90, 20],
        borderColor: 'rgba(0, 123, 255, 1)',
        backgroundColor: 'transparent',
        tension: 0.4,
        fill: false,
        pointRadius: 0
    }]
};

const active_config = {
    type: 'line',
    data: active,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false // Remove a legenda
            },
            title: {
                display: false // Remove o título
            },
            tooltip: {
                enabled: true, // Mostra os valores ao passar o mouse
                mode: 'index',
                intersect: false
            }
        },
        elements: {
            point: {
                radius: 5, // Tamanho dos pontos no gráfico
                backgroundColor: 'rgba(0, 123, 255, 1)', // Cor dos pontos
                borderWidth: 2, // Borda dos pontos
                borderColor: 'white' // Borda branca ao redor do ponto para destacar
            }
        },
        scales: {
            x: {
                type: 'category',
                offset: true,
                ticks: {
                    align: 'center' // Alinha melhor os rótulos no eixo X
                },
                grid: {
                    display: false
                }
            },
            y: {
                ticks: {
                    stepSize: 1, // Faz com que o eixo Y só mostre números inteiros
                    precision: 0, // Remove casas decimais
                    callback: function (value) {
                        return Number.isInteger(value) ? value : ''; // Garante que só mostre inteiros
                    }
                },
                grid: {
                    display: true,
                    color: '#e0e0e0',
                    lineWidth: 1
                }
            }
        }
    }
};
const active_ctx = document.getElementById('graph_active').getContext('2d');
let active_chart = new Chart(active_ctx, active_config);


//* Gráficos de inactive
const inactive = {
    labels: [
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_monday,
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_tuesday,
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_wednesday,
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_thursday,
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_friday,
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_saturday,
        GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_sunday,
    ],
    datasets: [{
        label: "",
        data: [50, 20, 10, 10, 40, 90, 20],
        borderColor: 'rgba(0, 123, 255, 1)',
        backgroundColor: 'transparent',
        tension: 0.4,
        fill: false,
        pointRadius: 0
    }]
};

const inactive_config = {
    type: 'line',
    data: inactive,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false // Remove a legenda
            },
            title: {
                display: false // Remove o título
            },
            tooltip: {
                enabled: true, // Mostra os valores ao passar o mouse
                mode: 'index',
                intersect: false
            }
        },
        elements: {
            point: {
                radius: 5, // Tamanho dos pontos no gráfico
                backgroundColor: 'rgba(255, 99, 132, 1)', // Cor dos pontos (vermelho para diferenciar)
                borderWidth: 2, // Borda dos pontos
                borderColor: 'white' // Borda branca ao redor do ponto para destacar
            }
        },
        scales: {
            x: {
                type: 'category',
                offset: true,
                ticks: {
                    align: 'center' // Alinha melhor os rótulos no eixo X
                },
                grid: {
                    display: false
                }
            },
            y: {
                ticks: {
                    stepSize: 1, // Faz com que o eixo Y só mostre números inteiros
                    precision: 0, // Remove casas decimais
                    callback: function (value) {
                        return Number.isInteger(value) ? value : ''; // Garante que só mostre inteiros
                    }
                },
                grid: {
                    display: true,
                    color: '#e0e0e0',
                    lineWidth: 1
                }
            }
        }
    }
};
const inactive_ctx = document.getElementById('graph_inactive').getContext('2d');
let inactive_chart = new Chart(inactive_ctx, inactive_config);


function filterDropdown(e) {
    const search = opt => {

        const text = e.value.toUpperCase();

        for (let i = 0; i < opt.length; i++) {

            const txtValue = opt[i].textContent || opt[i].innerText;

            if (txtValue.toUpperCase().indexOf(text) > -1) {
                opt[i].style.display = "block";
            } else {
                opt[i].style.display = "none";
            }
        }
    }

    if (e.id === "searchChannel") {
        if (fixedChannel) {

            document.getElementById("searchChannel").value = "";
            document.getElementById("selectedChannel").value = "";
            document.querySelector("#dropdown-channel .dropdown-content").style.display = "none";
            fixedChannel = false;
            return;
        }

        const dropdown = e.nextElementSibling;
        const option = dropdown.getElementsByTagName("a");

        search(option);
        document.querySelector("#dropdown-channel .dropdown-content").style.display = "block";
    }

    if (e.id === "searchBroadcast") {
        if (fixedbroadcast) {

            document.getElementById("searchBroadcast").value = "";
            document.getElementById("selectedBroadcast").value = "";
            document.querySelector("#dropdown-broadcast .dropdown-content").style.display = "none";
            fixedbroadcast = false;
            return;
        }

        const broadcast = document.querySelectorAll("#dropdown-broadcast a");
        const id_channel = document.getElementById("selectedChannel").value;

        if (id_channel === "") {
            search(broadcast);
        } else {
            search([...broadcast].filter(elm => elm.dataset.id_channel === id_channel));
        }

        document.querySelector("#dropdown-broadcast .dropdown-content").style.display = "block";
    }
}

function checkBroadcast(id_channel) {

    const broadcast = document.querySelectorAll("#dropdown-broadcast a");
    const list = [...broadcast];

    list.map((elm, idx) => {

        if (elm.dataset.id_channel === id_channel)
            elm.style.display = "block";
        else
            elm.style.display = "none";
    });
}

function openDropdown(e) {

    if (e.nextElementSibling.style.display === "block") {
        e.nextElementSibling.style.display = "none";
    } else {

        if (e.id === "searchChannel") {
            e.nextElementSibling.style.display = "block";
            return;
        }

        if (e.id === "searchBroadcast") {
            const id_channel = document.getElementById("selectedChannel").value;

            if (id_channel === "") {
                document.querySelectorAll("#dropdown-broadcast a").forEach(elm => elm.style.display = "block");
            } else {
                checkBroadcast(id_channel);
            }

            e.nextElementSibling.style.display = "block";
        }
    }
}

function closeDropdown(e) {
    e.nextElementSibling.style.display = "none";
}

function selectedChannel(e) {

    const searchChannel = document.getElementById("searchChannel");
    const selectedChannel = document.getElementById("selectedChannel");

    if (searchChannel !== null && searchChannel !== "") {

        searchChannel.value = e.text;
        selectedChannel.value = e.dataset.id_channel;
        fixedChannel = true;

        checkBroadcast(e.dataset.id_channel);
    }
}

async function getBroadcast(channel, period) {

    try {
        const formData = new FormData();

        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/getBroadcast`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();

            if (data === null) {
                broadcast.datasets[0].data = [0, 0, 0, 0, 0, 0, 0];
                broadcast.datasets[1].data = [0, 0, 0, 0, 0, 0, 0];
                broadcast.datasets[2].data = [0, 0, 0, 0, 0, 0, 0];
            } else {
                let labels = [];
                let tot_message_send = [];
                let tot_message_receipt = [];
                let tot_message_read = [];

                data.forEach((dataset, index) => {
                    labels.push(dataset.date_all);
                    tot_message_send.push(dataset.total_message_send);
                    tot_message_receipt.push(dataset.total_message_receipt);
                    tot_message_read.push(dataset.total_message_read);
                });

                broadcast.labels = labels;
                broadcast.datasets[0].data = tot_message_send;
                broadcast.datasets[1].data = tot_message_receipt;
                broadcast.datasets[2].data = tot_message_read;
            }

            broadcast_chart.update();
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getInteraction(channel, period) {

    try {
        const formData = new FormData();

        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/getInteraction`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();

            if (data === null) {
                interaction.labels = ['Sem dados nesse período'];
                interaction.datasets[0].data = [0, 0, 0, 0, 0, 0, 0];
                interaction.datasets[1].data = [0, 0, 0, 0, 0, 0, 0];
                interaction.datasets[2].data = [0, 0, 0, 0, 0, 0, 0];
            } else {
                let labels = [];
                let tot_contacts = [];
                let tot_active_contacts = [];
                let tot_inactive_contacts = [];

                data.forEach((dataset, index) => {
                    labels.push(dataset.date_creation);
                    tot_contacts.push(dataset.total_contacts);
                    tot_active_contacts.push(dataset.total_active_contacts);
                    tot_inactive_contacts.push(dataset.total_inactive_contacts);
                });

                interaction.labels = labels;
                interaction.datasets[0].data = tot_contacts;
                interaction.datasets[1].data = tot_active_contacts;
                interaction.datasets[2].data = tot_inactive_contacts;
            }

            interaction_chart.update();
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// async function getReactions(channel, period) {

//     try {
//         const formData = new FormData();

//         formData.append('id_channel', channel);
//         formData.append('period', period);
//         formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

//         const response = await fetch(`${document.location.origin}/dashboard/broadcast/getReactions`, {
//             method: "POST",
//             body: formData
//         });

//         if (response.ok) {
//             const data = await response.json();

//             if (data === null) {
//                 reaction.datasets[0].data = [0, 0, 0, 0, 0, 0, 0];
//             } else {
//                 let labels = [];
//                 let tot_reactions = [];

//                 data.forEach((dataset, index) => {
//                     labels.push(dataset.date_all);
//                     tot_reactions.push(dataset.total_reactions);
//                 });

//                 reaction.labels = labels;
//                 reaction.datasets[0].data = tot_reactions;
//             }

//             reaction_chart.update();
//         } else {
//             console.error('Error in the POST request:', response.status, response.statusText);
//         }
//     } catch (error) {
//         console.error('Erro:', error);
//     }
// }

async function getAllContacts(channel, period) {

    try {
        const formData = new FormData();

        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/getAllContacts`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();

            const totalActive = data.reduce((sum, item) => sum + parseFloat(item.percentage_active_contacts), 0);
            const totalInactive = data.reduce((sum, item) => sum + parseFloat(item.percentage_inactive_contacts), 0);

            let percentage_active_contacts = totalActive.toFixed(2);
            let percentage_inactive_contacts = totalInactive.toFixed(2);

            document.getElementById("base_active").textContent = `${percentage_active_contacts}%`;
            document.getElementById("base_inactive").textContent = `${percentage_inactive_contacts}%`;

            if (percentage_active_contacts >= 0) {
                document.getElementById("icon_base_active").className = 'fa fa-arrow-up';
                document.getElementById("icon_base_active").style.color = '#2dce89';

                document.getElementById("base_active").style.color = '#2dce89';
            }

            if (percentage_inactive_contacts == 0) {
                document.getElementById("icon_base_inactive").className = 'fa fa-arrow-up';
                document.getElementById("icon_base_inactive").style.color = '#f5365c';

                document.getElementById("base_inactive").style.color = '#f5365c';
            }

            if (percentage_inactive_contacts > 0) {
                document.getElementById("icon_base_inactive").className = 'fa fa-arrow-down';
                document.getElementById("icon_base_inactive").style.color = '#f5365c';

                document.getElementById("base_inactive").style.color = '#f5365c';
            }
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getCampaignStats(channel, period) {

    try {
        const formData = new FormData();

        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/getCampaignStats`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();

            let percentage_message_send = data[0].percentage_message_send;
            let percentage_message_receipt = data[0].percentage_message_receipt;

            document.getElementById("broadcast_send").textContent = `${data[0].percentage_message_send}%`;
            document.getElementById("broadcast_received").textContent = `${data[0].percentage_message_receipt}%`;

            if (percentage_message_send > 0) {
                document.getElementById("icon_broadcast_send").className = 'fa fa-arrow-up';
                document.getElementById("icon_broadcast_send").style.color = '#2dce89';

                document.getElementById("broadcast_send").style.color = '#2dce89';
            }

            if (percentage_message_send < 0 || percentage_message_send == 0) {
                document.getElementById("icon_broadcast_send").className = 'fa fa-arrow-down';
                document.getElementById("icon_broadcast_send").style.color = '#f5365c';

                document.getElementById("broadcast_send").style.color = '#f5365c';
            }

            if (percentage_message_receipt == 0) {
                document.getElementById("icon_broadcast_received").className = 'fa fa-arrow-up';
                document.getElementById("icon_broadcast_received").style.color = '#2dce89';

                document.getElementById("broadcast_received").style.color = '#2dce89';
            }

            if (percentage_message_receipt > 0) {
                document.getElementById("icon_broadcast_received").className = 'fa fa-arrow-down';
                document.getElementById("icon_broadcast_received").style.color = '#f5365c';

                document.getElementById("broadcast_received").style.color = '#f5365c';
            }
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getActiveContacts(channel, period) {
    try {
        const formData = new FormData();

        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/getActiveContacts`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();

            if (data === null) {
                active.datasets[0].data = [0, 0, 0, 0, 0, 0, 0];
                active.labels = [
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_monday,
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_tuesday,
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_wednesday,
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_thursday,
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_friday,
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_saturday,
                    GLOBAL_LANG.dashboard_broadcast_graph_active_weekday_sunday,
                ]
            } else {
                let labels = [];
                let active_contacts = [];

                data.forEach((dataset, index) => {
                    labels.push(dataset.date_creation);
                    active_contacts.push(dataset.active_contacts);
                });

                if (labels.length === 1) {
                    labels.push('');
                }

                if (active_contacts.length === 1) {
                    active_contacts.push(active_contacts[0]);
                }

                active.labels = labels;
                active.datasets[0].data = active_contacts;
            }

            active_chart.update();
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

async function getInactiveContacts(channel, period) {
    try {
        const formData = new FormData();

        formData.append('id_channel', channel);
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/getInactiveContacts`, {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            const data = await response.json();

            if (data === null) {
                inactive.datasets[0].data = [0, 0, 0, 0, 0, 0, 0];
                inactive.labels = [
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_monday,
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_tuesday,
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_wednesday,
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_thursday,
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_friday,
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_saturday,
                    GLOBAL_LANG.dashboard_broadcast_graph_inactive_weekday_sunday,
                ]
            } else {
                let labels = [];
                let inactive_contacts = [];

                data.forEach((dataset, index) => {
                    labels.push(dataset.date_creation);
                    inactive_contacts.push(dataset.inactive_contacts);
                });

                if (labels.length === 1) {
                    labels.push('');
                }

                if (inactive_contacts.length === 1) {
                    inactive_contacts.push(inactive_contacts[0]);
                }

                inactive.labels = labels;
                inactive.datasets[0].data = inactive_contacts;
            }

            inactive_chart.update();
        } else {
            console.error('Error in the POST request:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

function makeTableEngagement(data) {
    const no_data_contacts = document.querySelectorAll('.no-data-message.no-data-contact');
    const no_data_send = document.querySelectorAll('.no-data-message.no-data-send');

    no_data_contacts.forEach(element => {
        element.remove();
    });

    no_data_send.forEach(element => {
        element.remove();
    });

    if (document.getElementById('body-gan-contacts')) {
        document.getElementById('body-gan-contacts').innerHTML = '';
    }

    if (document.getElementById('body-loss-contacts')) {
        document.getElementById('body-loss-contacts').innerHTML = '';
    }

    if (document.getElementById('body-best-send')) {
        document.getElementById('body-best-send').innerHTML = '';
    }

    if (document.getElementById('body-less-send')) {
        document.getElementById('body-less-send').innerHTML = '';
    }

    if (data.most.most.length == 0) {
        noDataPeriod('most');
    }

    if (data.less.less.length == 0) {
        noDataPeriod('less');
    }

    if (data.most_received.list.length == 0) {
        noDataPeriod('most_received');
    }

    if (data.less_received.list.length == 0) {
        noDataPeriod('less_received');
    }


    const gold = {
        most: data.most.most[0],
        less: data.less.less[0],
        received_less: data.less_received.list[0],
        received_gain_less: data.less_received.gain[0],
        received_most: data.most_received.list[0],
        received_gain_most: data.most_received.gain[0],

    };

    const silver = {
        most: data.most.most[1],
        less: data.less.less[1],
        received_less: data.less_received.list[1],
        received_gain_less: data.less_received.gain[1],
        received_most: data.most_received.list[1],
        received_gain_most: data.most_received.gain[1],
    };

    const bronze = {
        most: data.most.most[2],
        less: data.less.less[2],
        received_less: data.less_received.list[2],
        received_gain_less: data.less_received.gain[2],
        received_most: data.most_received.list[2],
        received_gain_most: data.most_received.gain[2],
    };

    if (data.most.most.length == 1) {
        makeGoldEmblemLine(gold, 'most');
    }

    if (data.most.most.length == 2) {
        makeGoldEmblemLine(gold, 'most');
        makeSilverEmblemLine(silver, 'most');
    }

    if (data.most.most.length == 3) {
        makeGoldEmblemLine(gold, 'most');
        makeSilverEmblemLine(silver, 'most');
        makeBronzeEmblemLine(bronze, 'most');
    }

    if (data.less.less.length == 1) {
        lossGoldEmblemLine(gold, 'less');
    }

    if (data.less.less.length == 2) {
        lossGoldEmblemLine(gold, 'less');
        lossSilverEmblemLine(silver, 'less');
    }

    if (data.less.less.length == 3) {
        lossGoldEmblemLine(gold, 'less');
        lossSilverEmblemLine(silver, 'less');
        lossBronzeEmblemLine(bronze, 'less');
    }

    if (data.most_received.list.length == 1) {
        makeGoldEmblemLine(gold, 'most_received');
    }

    if (data.most_received.list.length == 2) {
        makeGoldEmblemLine(gold, 'most_received');
        makeSilverEmblemLine(silver, 'most_received');
    }

    if (data.most_received.list.length == 3) {
        makeGoldEmblemLine(gold, 'most_received');
        makeSilverEmblemLine(silver, 'most_received');
        makeBronzeEmblemLine(bronze, 'most_received');
    }

    if (data.less_received.list.length == 1) {
        lossGoldEmblemLine(gold, 'less_received');
    }

    if (data.less_received.list.length == 2) {
        lossGoldEmblemLine(gold, 'less_received');
        lossSilverEmblemLine(silver, 'less_received');
    }

    if (data.less_received.list.length == 3) {
        lossGoldEmblemLine(gold, 'less_received');
        lossSilverEmblemLine(silver, 'less_received');
        lossBronzeEmblemLine(bronze, 'less_received');
    }

}

function makeGoldEmblemLine(data, tag) {

    let table_rank = '';
    let tbody_id = '';
    let tr_id = '';
    let span_id = '';
    let name_text = '';

    if (!data || !tag) {
        return;
    }

    switch (tag) {
        case 'most':
            table_rank = 'rank-most-contact';
            tbody_id = 'body-gan-contacts';
            tr_id = 'first-best-line';
            span_id = 'best-one';
            name_text = data.most.name;
            break;
        case 'most_received':
            table_rank = 'rank-most-send';
            tbody_id = 'body-best-send';
            tr_id = 'first-best-line-send';
            span_id = 'best-one-send';
            name_text = data.received_most.name;
            break;

        default:
            break;
    }

    document.getElementById(table_rank).style.display = 'table';

    const tbody = document.getElementById(tbody_id);
    const tr = document.createElement('tr');
    tr.id = tr_id;

    const th = document.createElement('th');
    th.setAttribute('scope', 'row');

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('version', '1.1');
    svg.setAttribute('id', 'Layer_1');
    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    svg.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    svg.setAttribute('x', '0px');
    svg.setAttribute('y', '0px');
    svg.setAttribute('viewBox', '0 0 122.88 122.88');
    svg.setAttribute('style', 'enable-background:new 0 0 122.88 122.88; width: 25px; height: 25px;');
    svg.setAttribute('xml:space', 'preserve');


    const style = document.createElementNS('http://www.w3.org/2000/svg', 'style');
    style.setAttribute('type', 'text/css');
    style.textContent = '.st0 { fill-rule: evenodd; clip-rule: evenodd; }';
    svg.appendChild(style);


    const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('fill', '#FFD700');
    path.setAttribute('class', 'st0');
    path.setAttribute('d', 'M61.44,0l13.52,10.99l17.2-2.76l6.21,16.28l16.28,6.21l-2.76,17.2l10.99,13.52l-10.99,13.52l2.76,17.2 l-16.28,6.21l-6.21,16.28l-17.2-2.76l-13.52,10.99l-13.52-10.99l-17.2,2.76l-6.21-16.28L8.23,92.16l2.76-17.2L0,61.44l10.99-13.52 l-2.76-17.2l16.28-6.21l6.21-16.28l17.2,2.76L61.44,0L61.44,0z M61.44,39.54l6.34,15.49l16.69,1.24L71.7,67.09l3.98,16.26 l-14.23-8.81l-14.23,8.81l3.98-16.26L38.41,56.27l16.69-1.24L61.44,39.54L61.44,39.54z M61.44,26.71 c19.18,0,34.73,15.55,34.73,34.73c0,19.18-15.55,34.73-34.73,34.73S26.71,80.62,26.71,61.44C26.71,42.26,42.26,26.71,61.44,26.71 L61.44,26.71z');
    g.appendChild(path);
    svg.appendChild(g);


    th.appendChild(svg);

    const span = document.createElement('span');
    span.id = span_id;

    th.appendChild(span);

    tr.appendChild(th);

    makeEngagementLines(tr, data, tag);

    if (tbody.firstChild) {
        tbody.insertBefore(tr, tbody.firstChild);
    } else {
        tbody.appendChild(tr);
    }

    const name = truncateText(name_text, 32);
    document.getElementById(span_id).textContent = name;
}

function makeSilverEmblemLine(data, tag) {
    let tr_id = '';
    let span_id = '';
    let name_text = '';
    let tbody_id = '';

    if (!data || !tag) {
        return;
    }

    switch (tag) {
        case 'most':
            tr_id = 'second-best-line';
            span_id = 'best-two';
            name_text = data.most.name;
            tbody_id = 'body-gan-contacts';
            break;
        case 'most_received':
            tr_id = 'second-best-line-send';
            span_id = 'best-two-send';
            name_text = data.received_most.name;
            tbody_id = 'body-best-send';
            break;

        default:
            break;
    }

    const tr = document.createElement('tr');
    tr.id = tr_id;

    const th = document.createElement('th');
    th.setAttribute('scope', 'row');

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('version', '1.1');
    svg.setAttribute('id', 'Layer_1');
    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    svg.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    svg.setAttribute('x', '0px');
    svg.setAttribute('y', '0px');
    svg.setAttribute('viewBox', '0 0 122.88 122.88');
    svg.setAttribute('style', 'enable-background:new 0 0 122.88 122.88; width: 25px; height: 25px;');
    svg.setAttribute('xml:space', 'preserve');

    const style = document.createElementNS('http://www.w3.org/2000/svg', 'style');
    style.setAttribute('type', 'text/css');
    style.textContent = '.st0 { fill-rule: evenodd; clip-rule: evenodd; }';
    svg.appendChild(style);

    const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('fill', '#C0C0C0');
    path.setAttribute('class', 'st0');
    path.setAttribute('d', 'M61.44,0l13.52,10.99l17.2-2.76l6.21,16.28l16.28,6.21l-2.76,17.2l10.99,13.52l-10.99,13.52l2.76,17.2 l-16.28,6.21l-6.21,16.28l-17.2-2.76l-13.52,10.99l-13.52-10.99l-17.2,2.76l-6.21-16.28L8.23,92.16l2.76-17.2L0,61.44l10.99-13.52 l-2.76-17.2l16.28-6.21l6.21-16.28l17.2,2.76L61.44,0L61.44,0z M61.44,39.54l6.34,15.49l16.69,1.24L71.7,67.09l3.98,16.26 l-14.23-8.81l-14.23,8.81l3.98-16.26L38.41,56.27l16.69-1.24L61.44,39.54L61.44,39.54z M61.44,26.71 c19.18,0,34.73,15.55,34.73,34.73c0,19.18-15.55,34.73-34.73,34.73S26.71,80.62,26.71,61.44C26.71,42.26,42.26,26.71,61.44,26.71 L61.44,26.71z');
    g.appendChild(path);
    svg.appendChild(g);

    th.appendChild(svg);

    const span = document.createElement('span');
    span.id = span_id;

    th.appendChild(span);

    tr.appendChild(th);

    makeEngagementLines(tr, data, tag);

    const tbody = document.getElementById(tbody_id);

    if (tbody.firstChild) {
        tbody.insertBefore(tr, tbody.firstChild.nextSibling);
    } else {
        tbody.appendChild(tr);
    }

    const name = truncateText(name_text, 32);
    document.getElementById(span_id).textContent = name;
}

function makeBronzeEmblemLine(data, tag) {
    let tr_id = '';
    let span_id = '';
    let name_text = '';
    let tbody_id = '';

    if (!data || !tag) {
        return;
    }

    switch (tag) {
        case 'most':
            tr_id = 'third-best-line';
            span_id = 'best-tree';
            name_text = data.most.name;
            tbody_id = 'body-gan-contacts';
            break;
        case 'most_received':
            tr_id = 'third-best-line-send';
            span_id = 'best-tree-send';
            name_text = data.received_most.name;
            tbody_id = 'body-best-send';
            break;

        default:
            break;
    }

    const tr = document.createElement('tr');
    tr.id = 'third-best-line';

    const th = document.createElement('th');
    th.setAttribute('scope', 'row');

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('version', '1.1');
    svg.setAttribute('id', 'Layer_1');
    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    svg.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    svg.setAttribute('x', '0px');
    svg.setAttribute('y', '0px');
    svg.setAttribute('viewBox', '0 0 122.88 122.88');
    svg.setAttribute('style', 'enable-background:new 0 0 122.88 122.88; width: 25px; height: 25px;');
    svg.setAttribute('xml:space', 'preserve');

    const style = document.createElementNS('http://www.w3.org/2000/svg', 'style');
    style.setAttribute('type', 'text/css');
    style.textContent = '.st0 { fill-rule: evenodd; clip-rule: evenodd; }';
    svg.appendChild(style);

    const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('fill', '#CD7F32');
    path.setAttribute('class', 'st0');
    path.setAttribute('d', 'M61.44,0l13.52,10.99l17.2-2.76l6.21,16.28l16.28,6.21l-2.76,17.2l10.99,13.52l-10.99,13.52l2.76,17.2 l-16.28,6.21l-6.21,16.28l-17.2-2.76l-13.52,10.99l-13.52-10.99l-17.2,2.76l-6.21-16.28L8.23,92.16l2.76-17.2L0,61.44l10.99-13.52 l-2.76-17.2l16.28-6.21l6.21-16.28l17.2,2.76L61.44,0L61.44,0z M61.44,39.54l6.34,15.49l16.69,1.24L71.7,67.09l3.98,16.26 l-14.23-8.81l-14.23,8.81l3.98-16.26L38.41,56.27l16.69-1.24L61.44,39.54L61.44,39.54z M61.44,26.71 c19.18,0,34.73,15.55,34.73,34.73c0,19.18-15.55,34.73-34.73,34.73S26.71,80.62,26.71,61.44C26.71,42.26,42.26,26.71,61.44,26.71 L61.44,26.71z');
    g.appendChild(path);
    svg.appendChild(g);

    th.appendChild(svg);

    const span = document.createElement('span');
    span.id = span_id;
    th.appendChild(span);

    tr.appendChild(th);

    makeEngagementLines(tr, data, tag);

    const tbody = document.getElementById(tbody_id);
    if (tbody.firstChild) {
        tbody.insertBefore(tr, tbody.firstChild.nextSibling.nextSibling);
    } else {
        tbody.appendChild(tr);
    }
    const name = truncateText(name_text, 32);
    document.getElementById(span_id).textContent = name;
}

function makeEngagementLines(tr, data, tag) {
    let td_channel
    switch (tag) {
        case 'most':

            td_channel = document.createElement('td');
            td_channel.textContent = data.most.id;
            tr.appendChild(td_channel);

            const td_new_contacts = document.createElement('td');
            td_new_contacts.textContent = Number(data.most.total_contacts).toLocaleString('pt-BR');

            tr.appendChild(td_new_contacts);
            break;
        case 'most_received':
            td_channel = document.createElement('td');
            td_channel.textContent = data.received_most.id;
            tr.appendChild(td_channel);

            const td_percentage = document.createElement('td');

            const icon = document.createElement('i');
            icon.className = 'fas fa-arrow-up text-success mr-3';
            td_percentage.appendChild(icon);

            const span = document.createElement('span');
            span.textContent = data.received_gain_most + '%';
            td_percentage.appendChild(span);

            tr.appendChild(td_percentage);

            break;
        default:
            break;
    }
}

function lossGoldEmblemLine(data, tag) {

    let table_rank = '';
    let tbody_id = '';
    let tr_id = '';
    let span_id = '';
    let name_text = '';

    if (!data || !tag) {
        return;
    }

    switch (tag) {
        case 'less':
            table_rank = 'rank-less-contact';
            tbody_id = 'body-loss-contacts';
            tr_id = 'first-less-line';
            span_id = 'less-one';
            name_text = data.less.name;
            break;
        case 'less_received':
            table_rank = 'rank-less-send';
            tbody_id = 'body-less-send';
            tr_id = 'first-less-line-send';
            span_id = 'less-one-send';
            name_text = data.received_less.name;
            break;

        default:
            break;
    }

    document.getElementById(table_rank).style.display = 'table';

    const tbody = document.getElementById(tbody_id);
    const tr = document.createElement('tr');
    tr.id = tr_id;

    const th = document.createElement('th');
    th.setAttribute('scope', 'row');

    const icon_1 = document.createElement('i');
    icon_1.className = 'fas fa-arrow-down';
    icon_1.style.color = '#f5365c';
    th.appendChild(icon_1);

    const icon_2 = document.createElement('i');
    icon_2.className = 'fas fa-arrow-down';
    icon_2.style.color = '#f5365c';
    th.appendChild(icon_2);

    const icon_3 = document.createElement('i');
    icon_3.className = 'fas fa-arrow-down';
    icon_3.style.color = '#f5365c';
    th.appendChild(icon_3);

    const span = document.createElement('span');
    span.id = span_id;
    th.appendChild(span);
    tr.appendChild(th);

    lossContactLines(tr, data, tag);

    tbody.appendChild(tr);

    const name = truncateText(name_text, 32);

    document.getElementById(span_id).textContent = name;
}

function lossSilverEmblemLine(data, tag) {
    let tr_id = '';
    let span_id = '';
    let name_text = '';
    let tbody_id = '';

    if (!data || !tag) {
        return;
    }

    switch (tag) {
        case 'less':
            tr_id = 'second-less-line';
            span_id = 'less-two';
            name_text = data.less.name;
            tbody_id = 'body-loss-contacts';
            break;
        case 'less_received':
            tr_id = 'second-less-line-send';
            span_id = 'less-two-send';
            name_text = data.received_less.name;
            tbody_id = 'body-less-send';
            break;

        default:
            break;
    }
    const tbody = document.getElementById(tbody_id);
    const tr = document.createElement('tr');
    tr.id = tr_id;

    const th = document.createElement('th');
    th.setAttribute('scope', 'row');
    th.className = 'align-icon-text';

    const icon_1 = document.createElement('i');
    icon_1.className = 'fas fa-arrow-down';
    icon_1.style.color = '#f5365c';
    icon_1.style.marginRight = "0px";
    th.appendChild(icon_1);

    const icon_2 = document.createElement('i');
    icon_2.className = 'fas fa-arrow-down';
    icon_2.style.color = '#f5365c';
    icon_2.style.marginRight = "12px";
    th.appendChild(icon_2);

    const span = document.createElement('span');
    span.id = span_id;

    th.appendChild(span);
    tr.appendChild(th);

    lossContactLines(tr, data, tag);

    tbody.appendChild(tr);
    const name = truncateText(name_text, 32);

    document.getElementById(span_id).textContent = name;
}

function lossBronzeEmblemLine(data, tag) {
    let tr_id = '';
    let span_id = '';
    let name_text = '';
    let tbody_id = '';

    if (!data || !tag) {
        return;
    }

    switch (tag) {
        case 'less':
            tr_id = 'tree-less-line';
            span_id = 'less-tree';
            name_text = data.less.name;
            tbody_id = 'body-loss-contacts';
            break;
        case 'less_received':
            tr_id = 'tree-less-line-send';
            span_id = 'less-tree-send';
            name_text = data.received_less.name;
            tbody_id = 'body-less-send';
            break;

        default:
            break;
    }
    const tbody = document.getElementById(tbody_id);
    const tr = document.createElement('tr');
    tr.id = tr_id;

    const th = document.createElement('th');
    th.setAttribute('scope', 'row');
    th.className = 'align-icon-text';

    const icon_1 = document.createElement('i');
    icon_1.className = 'fas fa-arrow-down';
    icon_1.style.color = '#f5365c';
    icon_1.style.marginRight = "25px";
    th.appendChild(icon_1);

    const span = document.createElement('span');
    span.id = span_id;

    th.appendChild(span);
    tr.appendChild(th);

    lossContactLines(tr, data, tag);

    tbody.appendChild(tr);
    const name = truncateText(name_text, 32);

    document.getElementById(span_id).textContent = name;
}

function lossContactLines(tr, data, tag) {
    let td_channel;

    switch (tag) {
        case 'less':
            td_channel = document.createElement('td');
            td_channel.textContent = data.less.id;
            tr.appendChild(td_channel);

            const td_new_contacts = document.createElement('td');
            td_new_contacts.textContent = Number(data.less.total_contacts).toLocaleString('pt-BR');
            tr.appendChild(td_new_contacts);

            break;
        case 'less_received':
            td_channel = document.createElement('td');
            td_channel.textContent = data.received_less.id;
            tr.appendChild(td_channel);

            const td_percentage = document.createElement('td');

            const icon = document.createElement('i');
            icon.className = 'fas fa-arrow-up text-success mr-3';
            td_percentage.appendChild(icon);

            const span = document.createElement('span');
            span.textContent = data.received_gain_less + '%';
            td_percentage.appendChild(span);

            tr.appendChild(td_percentage);
            break;

        default:
            break;
    }
}

function truncateText(text, maxLength) {
    if (text.length > maxLength) {
        return text.slice(0, maxLength) + '...';
    }
    return text;
}

function noDataPeriod(type) {
    let contact = '';
    let name_class = '';

    switch (type) {
        case 'most':
            document.getElementById('rank-most-contact').style.display = 'none';
            contact = document.getElementById('table-rank-most-contact');
            name_class = 'no-data-message no-data-contact';
            break;
        case 'less':
            document.getElementById('rank-less-contact').style.display = 'none';
            contact = document.getElementById('table-rank-less-contact');
            name_class = 'no-data-message no-data-contact';
            break;
        case 'most_received':
            document.getElementById('rank-most-send').style.display = 'none';
            contact = document.getElementById('table-rank-most-send');
            name_class = 'no-data-message no-data-send';
            break;
        case 'less_received':
            document.getElementById('rank-less-send').style.display = 'none';
            contact = document.getElementById('table-rank-less-send');
            name_class = 'no-data-message no-data-send';
            break;
        default:
            break;
    }

    const span = document.createElement('spam');
    span.className = 'no-data-message no-data-contact';
    span.style.height = '100px';
    span.style.width = '100%';
    span.style.backgroundColor = '#f6f9fc';
    span.textContent = GLOBAL_LANG.dashboard_broadcast_table_no_for_the_period;

    contact.appendChild(span);
}

async function searchForBroadcast() {

    const period = document.getElementById("selectBroadcastPeriod").value;
    const channel = document.getElementById("selectBroadcastChannel")?.value || '';

    if (channel === '') {
        return;
    }

    getBroadcast(channel, period);
    getInteraction(channel, period);
    // getReactions(channel, period);
    getAllContacts(channel, period);
    getCampaignStats(channel, period);
    getActiveContacts(channel, period);
    getInactiveContacts(channel, period);
}

async function searchForEngagement() {

    const period = document.getElementById("selectEngagementPeriod").value;

    try {

        const formData = new FormData();
        formData.append('period', period);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        const response = await fetch(`${document.location.origin}/dashboard/broadcast/engagement`, {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        makeTableEngagement(data);

    } catch (error) {
        console.error('Erro:', error);
    }
}

document.addEventListener('DOMContentLoaded', function () {

    searchForBroadcast();
    searchForEngagement();


    document.getElementById("selectBroadcastChannel").addEventListener("change", searchForBroadcast);
    document.getElementById("selectBroadcastPeriod").addEventListener("change", searchForBroadcast);
    document.getElementById("selectEngagementPeriod").addEventListener("change", searchForEngagement);
});


window.addEventListener('resize', () => window.location.reload(), true);

const graphContainerBroadcast = document.getElementById('graph_broadcast').parentElement;
const graphContainerInteraction = document.getElementById('graph_interaction').parentElement;
const graphContainerActive = document.getElementById('graph_active').parentElement;
const graphContainerInactive = document.getElementById('graph_inactive').parentElement;

const resizeObserver = new ResizeObserver(() => {

    destroyChart();
    createChart();

    if (broadcast_chart)
        broadcast_chart.destroy();

    if (interaction_chart)
        interaction_chart.destroy();

    if (active_chart)
        active_chart.destroy();

    if (inactive_chart)
        inactive_chart.destroy();

    const broadcast_ctx = document.getElementById('graph_broadcast').getContext('2d');
    broadcast_chart = new Chart(broadcast_ctx, broadcast_config);

    const interaction_ctx = document.getElementById('graph_interaction').getContext('2d');
    interaction_chart = new Chart(interaction_ctx, interaction_config);

    const active_chart_ctx = document.getElementById('graph_active').getContext('2d');
    active_chart = new Chart(active_chart_ctx, active_config);

    const inactive_chart_ctx = document.getElementById('graph_inactive').getContext('2d');
    inactive_chart = new Chart(inactive_chart_ctx, inactive_config);
});

resizeObserver.observe(graphContainerBroadcast);
resizeObserver.observe(graphContainerInteraction);
resizeObserver.observe(graphContainerActive);
resizeObserver.observe(graphContainerInactive);



$('#exportConfirmation').on('click', async function () {
    $('#exportModal').modal('hide');

    const { Document, Packer, Paragraph, ImageRun, Header, Footer, AlignmentType, TextRun } = window.docx;

    const sections = [];

    async function getImageBufferFromDiv(divId) {
        const div = document.getElementById(divId);
        const { width, height } = div.getBoundingClientRect();

        const canvas = await html2canvas(div);
        const dataUrl = canvas.toDataURL("image/png");
        const response = await fetch(dataUrl);
        const imageBuffer = await response.arrayBuffer();

        return { buffer: imageBuffer, width, height };
    }

    async function getImageBufferFromUrl(url) {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Error ao carregar a imagem desde: ${url}`);
        }
        return await response.arrayBuffer();
    }

    // HEADER
    const headerImageBuffer = await getImageBufferFromUrl("https://files.talkall.com.br:3000/v/2025311/sRJHRy0Ccce1G5sGSkJpSL44oRmpZ2WjXCBa04hvarE7dKexjFR5jBBZCLZ4ZmHB.jpeg");
    const headerImageParagraph = new Paragraph({
        spacing: { before: 0, after: 0 },
        indent: { left: -1440 },
        alignment: AlignmentType.LEFT,
        children: [
            new ImageRun({
                data: headerImageBuffer,
                transformation: {
                    width: 795,
                    height: 113,
                },
            }),
        ],
    });

    // FOOTER
    const footerImageBuffer = await getImageBufferFromUrl("https://files.talkall.com.br:3000/v/2025311/9xDdqSQBX1BpeYeCjEZmyhzTUBQKXYCj8nZXlE4kKXg2Zp8C6vwrNtnsSrFufkdz.jpeg");
    const footerImageParagraph = new Paragraph({
        spacing: { before: 0, after: 0 },
        indent: { left: -1440 },
        alignment: AlignmentType.LEFT,
        children: [
            new ImageRun({
                data: footerImageBuffer,
                transformation: {
                    width: 795,
                    height: 67,
                },
            }),
        ],
    });

    const selectElement = document.getElementById('selectBroadcastPeriod');
    const selectedText = selectElement.options[selectElement.selectedIndex].text;

    const selectBroadcastChannel = document.getElementById('selectBroadcastChannel');
    const selectBroadcasText = selectBroadcastChannel.options[selectBroadcastChannel.selectedIndex].text;

    // TÍTULO PRINCIPAL
    sections.push(
        new Paragraph({
            children: [
                new TextRun({
                    text: `${GLOBAL_LANG.dashboard_broadcast_table_rank_channel}: ${selectBroadcasText}  ${GLOBAL_LANG.dashboard_broadcast_filter_period}: ${selectedText}`,
                    size: 30,
                    bold: true,
                }),
            ],
            alignment: AlignmentType.CENTER,
            spacing: { after: 400 }, // Espaçamento após título principal
        }),
    );

    // GRÁFICOS
    const canvasIds = [
        { id: "graph_broadcast", title: "Broadcast" },
        { id: "graph_interaction", title: GLOBAL_LANG.dashboard_broadcast_graph_interaction_title },
        { id: "graph_active", title: GLOBAL_LANG.dashboard_broadcast_graph_active_title },
        { id: "graph_inactive", title: GLOBAL_LANG.dashboard_broadcast_header_base_inactive },
    ];

    for (const item of canvasIds) {
        const canvas = document.getElementById(item.id);
        const dataUrl = canvas.toDataURL("image/png");
        const response = await fetch(dataUrl);
        const imageBuffer = await response.arrayBuffer();

        sections.push(
            new Paragraph({
                children: [
                    new TextRun({
                        text: item.title,
                        size: 28,
                        bold: true,
                    }),
                ],
                alignment: AlignmentType.LEFT,
                spacing: { after: 200 },
            }),
            new Paragraph({
                children: [
                    new ImageRun({
                        data: imageBuffer,
                        transformation: {
                            width: 600,
                            height: 300,
                        },
                    }),
                ],
            }),
            new Paragraph(""),
            new Paragraph(""),
            new Paragraph(""),
        );
    }

    const selectElement2 = document.getElementById('selectEngagementPeriod');
    const selectedText2 = selectElement2.options[selectElement2.selectedIndex].text;

    // TÍTULO EXTRA EM NOVA PÁGINA
    sections.push(
        new Paragraph({
            children: [
                new TextRun({
                    text: `${GLOBAL_LANG.dashboard_broadcast_filter_period}: ${selectedText2}`,
                    size: 30,
                    bold: true,
                }),
            ],
            alignment: AlignmentType.CENTER,
            spacing: { after: 600 }, // Aumenta espaçamento abaixo do título
            pageBreakBefore: true,   // Faz com que o título comece em uma nova página
        }),
    );

    // TABELAS
    const tableIds = [
        { id: "table-rank-most-contact", title: GLOBAL_LANG.dashboard_broadcast_export_rack_most_contact },
        { id: "table-rank-less-contact", title: GLOBAL_LANG.dashboard_broadcast_export_rack_less_contact },
        { id: "table-rank-most-send", title: GLOBAL_LANG.dashboard_broadcast_export_rack_most_send },
        { id: "table-rank-less-send", title: GLOBAL_LANG.dashboard_broadcast_export_rack_less_send },
    ];

    for (const item of tableIds) {
        const maxWidth = 600;
        const { buffer, width, height } = await getImageBufferFromDiv(item.id);

        sections.push(
            new Paragraph({
                children: [
                    new TextRun({
                        text: item.title,
                        size: 28,
                        bold: true,
                    }),
                ],
                alignment: AlignmentType.LEFT,
                spacing: { after: 300 }, // Espaçamento abaixo do título de cada tabela
            }),
            new Paragraph({
                children: [
                    new ImageRun({
                        data: buffer,
                        transformation: {
                            width: maxWidth,
                            height: (height * maxWidth) / width,
                        },
                    }),
                ],
                spacing: { after: 300 }, // Espaçamento abaixo da tabela
            }),
            new Paragraph(""),
            new Paragraph(""),
        );
    }

    // DOCUMENTO FINAL
    const doc = new Document({
        creator: "talkall.com.br",
        title: "Dashboard",
        sections: [
            {
                properties: {
                    page: {
                        margin: {
                            top: 1440,
                            bottom: 1440,
                            left: 1440,
                            right: 1440,
                        },
                    },
                },
                children: sections,
                headers: {
                    default: new Header({
                        children: [headerImageParagraph], // Header global
                    }),
                },
                footers: {
                    default: new Footer({
                        children: [footerImageParagraph], // Footer global
                    }),
                },
            },
        ],
    });

    const blob = await Packer.toBlob(doc);
    saveAs(blob, "dashboard.docx");
});