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
    public function graph_3($request)
    {
        $graphs = $this->partner->statisticCharts("yearly",$request["type"])->content;
        $data = [];
        $label = [];
        $background = [];
        $border = [];
        foreach($graphs as $value) {
            $label[] = $value->display;
            $data[] = $value->count;
            $background[] = "rgba(255, 99, 132, 0.2)";
            $border[] = "rgba(255, 99, 132)";
        }
        echo json_encode([
            "type" => "bar",
            "data" => [
                "labels" => array_reverse($label),
                "datasets" => array([
                    "label" => 'Transactions',
                    "data" => array_reverse($data),
                    "lineTension" => 0,
                    "backgroundColor" => $background,
                    "borderColor" => $border,
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
}