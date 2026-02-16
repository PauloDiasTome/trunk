<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExportController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Export_model', '', TRUE);
        $this->lang->load('export_lang', $this->session->userdata('language'));
    }

    function SaveExport()
    {
        $type = $this->input->get("type");
        $resp = true;
        
        switch ($type) {
            case 'shortlink':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'contact':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "search_type",
                            "search_type": "' . trim($this->input->get('search_type')) . '"
                        },
                        {
                            "type": "channel",
                            "channel": "' . trim($this->input->get('channel')) . '"
                        },
                        {
                            "type": "persona",
                            "persona": "' . trim($this->input->get('persona')) . '"
                        },
                        {
                            "type": "label",
                            "label": "' . trim($this->input->get('label')) . '"
                        },
                        {
                            "type": "responsible",
                            "responsible": "' . trim($this->input->get('responsible')) . '"
                        },
                        {
                            "type": "situation",
                            "situation": "' . trim($this->input->get('situation')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'label':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'blocklist':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'community':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'community_participant':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "community",
                            "community": "' . trim($this->input->get('community')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'user':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "user_group",
                            "user_group": "' . trim($this->input->get('sector')) . '"
                        },
                        {
                            "type": "status",
                            "status": "' . trim($this->input->get('situation')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'replies':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'userGroup':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'permission':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'category':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'userCall':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationWhatsappBroadcast':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "channel",
                                "channel": "' . trim($this->input->get('channel')) . '"
                            },
                            {
                                "type": "status",
                                "status": "' . trim($this->input->get('status')) . '"
                            },
                            {
                                "type": "situation",
                                "situation": "' . trim($this->input->get('situation')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationWhatsappBroadcastCommunity':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "community",
                                "community": "' . trim($this->input->get('community')) . '"
                            },
                            {
                                "type": "channel",
                                "channel": "' . trim($this->input->get('channel')) . '"
                            },
                            {
                                "type": "status",
                                "status": "' . trim($this->input->get('status')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationWhatsappBroadcastNewsletter':

                $json_str = '{
                            "filters": [
                                {
                                    "type": "search",
                                    "search": "' . trim($this->input->get('search')) . '"
                                },
                                {
                                    "type": "channel",
                                    "channel": "' . trim($this->input->get('channel')) . '"
                                },
                                {
                                    "type": "status",
                                    "status": "' . trim($this->input->get('status')) . '"
                                },
                                {
                                    "type": "column",
                                    "column": "' . trim($this->input->get('column')) . '"
                                },
                                {
                                    "type": "order",
                                    "order": "' . trim($this->input->get('order')) . '"
                                },
                                {   "type": "language",
                                    "language": "' . $this->session->userdata('language') . '"
                                },
                                {
                                    "type": "period",
                                    "period": [
                                        {
                                            "start": "' . trim($this->input->get('dt_start')) . '",
                                            "end": "' . trim($this->input->get('dt_end')) . '"
                                        }
                                    ]
                                }
                            ]
                        }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationWhatsappStatus':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "channel",
                                "channel": "' . trim($this->input->get('channel')) . '"
                            },
                            {
                                "type": "status",
                                "status": "' . trim($this->input->get('status')) . '"
                            },
                            {
                                "type": "situation",
                                "situation": "' . trim($this->input->get('situation')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationWhatsappBroadcastWaba':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "channel",
                                "channel": "' . trim($this->input->get('channel')) . '"
                            },
                            {
                                "type": "status",
                                "status": "' . trim($this->input->get('status')) . '"
                            },
                            {
                                "type": "situation",
                                "situation": "' . trim($this->input->get('situation')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationFacebookPost':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "channel",
                                "channel": "' . trim($this->input->get('channel')) . '"
                            },
                            {
                                "type": "status",
                                "status": "' . trim($this->input->get('status')) . '"
                            },
                            {
                                "type": "situation",
                                "situation": "' . trim($this->input->get('situation')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationInstagramPost':

                $json_str = '{
                            "filters": [
                                {
                                    "type": "search",
                                    "search": "' . trim($this->input->get('search')) . '"
                                },
                                {
                                    "type": "channel",
                                    "channel": "' . trim($this->input->get('channel')) . '"
                                },
                                {
                                    "type": "status",
                                    "status": "' . trim($this->input->get('status')) . '"
                                },
                                {
                                    "type": "situation",
                                    "situation": "' . trim($this->input->get('situation')) . '"
                                },
                                {
                                    "type": "column",
                                    "column": "' . trim($this->input->get('column')) . '"
                                },
                                {
                                    "type": "order",
                                    "order": "' . trim($this->input->get('order')) . '"
                                },
                                {   "type": "language",
                                    "language": "' . $this->session->userdata('language') . '"
                                },
                                {
                                    "type": "period",
                                    "period": [
                                        {
                                            "start": "' . trim($this->input->get('dt_start')) . '",
                                            "end": "' . trim($this->input->get('dt_end')) . '"
                                        }
                                    ]
                                }
                            ]
                        }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'publicationBroadcastTv':

                $json_str = '{
                                "filters": [
                                    {
                                        "type": "search",
                                        "search": "' . trim($this->input->get('search')) . '"
                                    },
                                    {
                                        "type": "channel",
                                        "channel": "' . trim($this->input->get('channel')) . '"
                                    },
                                    {
                                        "type": "status",
                                        "status": "' . trim($this->input->get('status')) . '"
                                    },
                                    {
                                        "type": "column",
                                        "column": "' . trim($this->input->get('column')) . '"
                                    },
                                    {
                                        "type": "order",
                                        "order": "' . trim($this->input->get('order')) . '"
                                    },
                                    {   "type": "language",
                                        "language": "' . $this->session->userdata('language') . '"
                                    },
                                    {
                                        "type": "period",
                                        "period": [
                                            {
                                                "start": "' . trim($this->input->get('dt_start')) . '",
                                                "end": "' . trim($this->input->get('dt_end')) . '"
                                            }
                                        ]
                                    }
                                ]
                            }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'broadcastSMS':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "status",
                                "status": "' . trim($this->input->get('status')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'ticket':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "ticket_type",
                            "ticket_type": "' . trim($this->input->get('ticket_type')) . '"
                        },
                        {
                            "type": "ticket_status",
                            "ticket_status": "' . trim($this->input->get('ticket_status')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '" 
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'ticketType':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'ticketSubtype':

                $json_str = '{
                    "filters": [
                        {
                            "type": "id_type",
                            "id_type": "' . trim($this->input->get('id_type')) . '"
                        },
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'ticketStatus':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'ticketSLA':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'order':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "status",
                            "status": "' . trim($this->input->get('status')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'orderStatus':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'payment':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportCall':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "channel",
                            "channel": "' . trim($this->input->get('channel')) . '"
                        },
                        {
                            "type": "categories",
                            "categories": "' . trim($this->input->get('categories')) . '"
                        },
                        {
                            "type": "label",
                            "label": "' . trim($this->input->get('label')) . '"
                        },
                        {
                            "type": "user",
                            "user": "' . trim($this->input->get('user')) . '"
                        },
                        {
                            "type": "user_group",
                            "user_group": "' . trim($this->input->get('sector')) . '"
                        },
                        {
                            "type": "situation",
                            "situation": "' . trim($this->input->get('situation')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportChatbotAnalytical':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "channel",
                            "channel": "' . trim($this->input->get('channel')) . '"
                        },

                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportChatbotQuantitative':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {
                            "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportWaitingService':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "user_group",
                            "user_group": "' . trim($this->input->get('sector')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportInteractionSynthetic':

                $json_str = '{
                    "filters": [
                        {
                            "type": "situation",
                            "situation": "' . trim($this->input->get('situation')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportEvaluate':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "evaluation",
                            "evaluation": "' . trim($this->input->get('assessment')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportBroadcastSynthetic':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "channel",
                                "channel": "' . trim($this->input->get('channel')) . '"
                            },
                            {
                                "type": "campaign_type",
                                "campaign_type": "' . trim($this->input->get('campaign_type')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            },
                            {
                                "type": "period",
                                "period": [
                                    {
                                        "start": "' . trim($this->input->get('dt_start')) . '",
                                        "end": "' . trim($this->input->get('dt_end')) . '"
                                    }
                                ]
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'product':

                $json_str = '{
                    "filters": [
                        {
                            "type": "title_or_cod",
                            "title_or_cod": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "status",
                            "status": "' . trim($this->input->get('situation')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'bot':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'faq':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'workTime':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'templateMsg':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'clientCompany':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'reportMessageHistory':

                $json_str = '{
                    "filters": [
                        {
                            "type": "id_chat_list",
                            "id_chat_list": "' . trim($this->input->get('id_chat_list')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        },
                        {
                            "type": "period",
                            "period": [
                                {
                                    "start": "' . trim($this->input->get('dt_start')) . '",
                                    "end": "' . trim($this->input->get('dt_end')) . '"
                                }
                            ]
                        }
                    ]
                }';

                $resp = $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'clientCompany':

                $json_str = '{
                    "filters": [
                        {
                            "type": "search",
                            "search": "' . trim($this->input->get('search')) . '"
                        },
                        {
                            "type": "column",
                            "column": "' . trim($this->input->get('column')) . '"
                        },
                        {
                            "type": "order",
                            "order": "' . trim($this->input->get('order')) . '"
                        },
                        {   "type": "language",
                            "language": "' . $this->session->userdata('language') . '"
                        }
                    ]
                }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'personas':

                $json_str = '{
                        "filters": [
                            {
                                "type": "search",
                                "search": "' . trim($this->input->get('search')) . '"
                            },
                            {
                                "type": "column",
                                "column": "' . trim($this->input->get('column')) . '"
                            },
                            {
                                "type": "order",
                                "order": "' . trim($this->input->get('order')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;

            case 'export_personas_contacts':

                $json_str = '{
                        "filters": [
                            {
                                "type": "id_group_contact",
                                "id_group_contact": "' . trim($this->input->get('id_group_contact')) . '"
                            },
                            {   "type": "language",
                                "language": "' . $this->session->userdata('language') . '"
                            }
                        ]
                    }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;
            case 'publicationTelegramChannel':

                $json_str = '{
                                "filters": [
                                    {
                                        "type": "search",
                                        "search": "' . trim($this->input->get('search')) . '"
                                    },
                                    {
                                        "type": "channel",
                                        "channel": "' . trim($this->input->get('channel')) . '"
                                    },
                                    {
                                        "type": "status",
                                        "status": "' . trim($this->input->get('status')) . '"
                                    },
                                    {
                                        "type": "column",
                                        "column": "' . trim($this->input->get('column')) . '"
                                    },
                                    {
                                        "type": "order",
                                        "order": "' . trim($this->input->get('order')) . '"
                                    },
                                    {   "type": "language",
                                        "language": "' . $this->session->userdata('language') . '"
                                    },
                                    {
                                        "type": "period",
                                        "period": [
                                            {
                                                "start": "' . trim($this->input->get('dt_start')) . '",
                                                "end": "' . trim($this->input->get('dt_end')) . '"
                                            }
                                        ]
                                    }
                                ]
                            }';

                $this->Export_model->SaveExport($this->session->userdata('email_user'), $type, $json_str);
                break;
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resp));
    }
}
