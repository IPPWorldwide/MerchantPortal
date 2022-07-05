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
        $invoices = $this->partner->Listinvoices();
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
    public function graph_2($request)
    {
        if($request['type'] == 'today'){
            return;
        }
        $invoices = $this->partner->Listinvoices();
        foreach($invoices as $invoice){
            $transaction_date = new DateTime("@".$invoice->period_start);
            // print_r($transaction_date);
        }
        // echo next($invoices)->id;
        // exit();
        $data = [];
        $label = [];
        $date = new DateTime(date("Y-m-01 00:00:00"));
        $j = current($invoices)->id;
        // print_r("____________ start loop _____________\n");
        // print_r($date);
        for($i = 1; $i <= $request['type']; $i++){
            $transaction_date = new DateTime("@".$invoices->{$j}->period_start);
            if($transaction_date < $date){
                $label[] = $date->format("d M");
                $data[] = 0;
                // print_r($date);
                // print_r("____________ transaction date < date _____________\n");
                $date->sub(new DateInterval("P1D"));
                continue;
            }
            $data_this_date = 0;
            // print_r("____________ transaction date condition start _____________\n");
            // print_r($date);
            // print_r($transaction_date);
            // print_r("____________ transaction date condition end _____________\n");
            // print_r("____________ transaction date while start _____________\n");
            while($transaction_date->format('Y-m-d') == $date->format('Y-m-d')){
                // print_r("____________ transaction date while continue _____________\n");
                $data_this_date += $invoices->{$j}->amount;
                $j = next($invoices)->id;
                // echo $j;
                $transaction_date = new DateTime("@".$invoices->{$j}->period_start);
            }
            // print_r("____________ transaction date while end _____________\n");
            $label[] = $date->format("d M");
            $data[] = $data_this_date;
            // print_r($date);
            // print_r("____________ transaction date == date _____________\n");
            $date->sub(new DateInterval("P1D"));
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