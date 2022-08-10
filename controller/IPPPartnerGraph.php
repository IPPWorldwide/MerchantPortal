<?php
class IPPPartnerGraph {
    private $user_id;
    private $session_id;
    private $request;
    private $partner;

    function __construct($partner, $request,$id = "",$session_id = "") {
        $this->request = $request;

        if($id != "")
            $this->user_id = $id;
        if($session_id != "")
            $this->session_id = $session_id;
        $this->partner = $partner;
    }

    public function graph_1($request)
    {
        $graphs = $this->partner->statisticCharts("live",$request["type"])->content;
        $data = [];
        $label = [];
        foreach($graphs as $value) {
            $label[] = $value->display;
            $data[] = $value->count;
        }
        echo json_encode([
            "type" => "line",
            "data" => [
                "labels" => array_reverse($label),
                "datasets" => array([
                    "label" => "Transactions",
                    "data" => array_reverse($data),
                    "backgroundColor" => 'transparent',
                    "borderColor" => '#007bff',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'#007bff',
                    "tension" => 0.5,
                ])
            ],
            "options" => [
                "scales" => [
                    "yAxes" => [
                        [
                            "ticks" => [
                                "beginAtZero" => false
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => true
                ],
                "interaction" => [
                    "intersect" => false,
                ],
            ]
        ]);
    }
    public function graph_2($request)
    {
        $graphs = $this->partner->statisticCharts("daily",$request["type"])->content;
        $data = [];
        $label = [];
        foreach($graphs as $value) {
            $label[] = $value->display;
            $data[] = $value->count;
        }
        echo json_encode([
            "type" => "line",
            "data" => [
                "labels" => array_reverse($label),
                "datasets" => array([
                    "label" => "Daily transactions",
                    "data" => array_reverse($data),
                    "backgroundColor" => 'transparent',
                    "borderColor" => '#007bff',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'#007bff',
                    "tension" => 0.5,
                ])
            ],
            "options" => [
                "scales" => [
                    "yAxes" => [
                        [
                            "ticks" => [
                                "beginAtZero" => false
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => true
                ],
                "interaction" => [
                    "intersect" => false,
                ],
            ]
        ]);
    }
    public function graph_3()
    {
        $data = [];
        $label = [];
        $today = new DateTime();
        for($i = 1; $i <= 12 ; $i++){
            $label[] = $today->format("F");
            $today->add(new DateInterval("P1M"));
            $data[] = rand(14000, 50000);
        }
        echo json_encode([
            "type" => "bar",
            "data" => [
                "labels" => $label,
                "datasets" => array([
                    "label" => 'Bar Chart',
                    "data" => $data,
                    "lineTension" => 0,
                    "backgroundColor" => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                        'rgba(156, 203, 207, 0.2)',
                        'rgba(231, 203, 207, 0.2)',
                        'rgba(109, 203, 207, 0.2)',
                        'rgba(199, 203, 207, 0.2)',
                        'rgba(129, 203, 207, 0.2)',
                    ],
                    "borderColor" => [
                        'rgba(255, 99, 132)',
                        'rgba(255, 159, 64)',
                        'rgba(255, 205, 86)',
                        'rgba(75, 192, 192)',
                        'rgba(54, 162, 235)',
                        'rgba(153, 102, 255)',
                        'rgba(201, 203, 207)',
                        'rgba(156, 203, 207)',
                        'rgba(231, 203, 207)',
                        'rgba(109, 203, 207)',
                        'rgba(199, 203, 207)',
                        'rgba(129, 203, 207)',
                    ],
                    "borderWidth" => 1,
                ])
            ],
            "options" => [
                "scales" => [
                    "yAxes" => [
                        [
                            "ticks" => [
                                "beginAtZero" => false
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => false
                ]
            ]
        ]);
    }
    public function graph_4()
    {
        $data = [];
        $data2 = [];
        $label = [];
        $today = new DateTime();
        for($i = 1; $i <= 7 ; $i++){
            $label[] = $today->format("l");
            $today->add(new DateInterval("P1D"));
            $data[] = rand(14000, 50000);
            $data2[] = rand(14000, 50000);
        }
        echo json_encode([
            "type" => "line",
            "data" => [
                "labels" => $label,
                "datasets" => array([
                    "label" => "Data 1",
                    "data" => $data,
                    "backgroundColor" => 'transparent',
                    "borderColor" => '#007bff',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'#007bff',
                    "tension" => 0.5,
                ],[
                    "label" => "Data 2",
                    "data" => $data2,
                    "backgroundColor" => 'transparent',
                    "borderColor" => 'rgb(75, 192, 192)',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'rgb(75, 192, 192)',
                    "tension" => 0.5
                ])
            ],
            "options" => [
                "scales" => [
                    "yAxes" => [
                        [
                            "ticks" => [
                                "beginAtZero" => false
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => true
                ],
                "interaction" => [
                    "intersect" => false,
                ],
            ]
        ]);
    }
    public function graph_5()
    {
        $data = [];
        $data2 = [];
        $label = [];
        $today = new DateTime();
        for($i = 1; $i <= 7 ; $i++){
            $label[] = $today->format("l");
            $today->add(new DateInterval("P1D"));
            $data[] = rand(14000, 50000);
            $data2[] = rand(14000, 50000);
        }
        echo json_encode([
            "type" => "line",
            "data" => [
                "labels" => $label,
                "datasets" => array([
                    "label" => "Data 1",
                    "data" => $data,
                    "backgroundColor" => 'transparent',
                    "borderColor" => '#007bff',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'#007bff',
                    "tension" => 0.5,
                ],[
                    "label" => "Data 2",
                    "data" => $data2,
                    "backgroundColor" => 'transparent',
                    "borderColor" => 'rgb(75, 192, 192)',
                    "borderWidth" => 2,
                    "pointBackgroundColor" =>'rgb(75, 192, 192)',
                    "tension" => 0.5
                ])
            ],
            "options" => [
                "scales" => [
                    "yAxes" => [
                        [
                            "ticks" => [
                                "beginAtZero" => false
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => true
                ],
                "interaction" => [
                    "intersect" => false,
                ],
            ]
        ]);
    }
}