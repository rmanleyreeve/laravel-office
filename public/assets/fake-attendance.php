<?php
function randtime($min,$max){
    $t1 = strtotime(date('Y-m-d'). "{$min}:00:00");
    $t2 = strtotime(date('Y-m-d'). "{$max}:00:00")-1;
    $rt = rand($t1,$t2);
    return [date('Y-m-d H:i:s', $rt), date('H:i:s', $rt)];
}

?>[
    {
        "uid": "3",
        "name": "Cheryl Bell",
        "role": "Secretary",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('07','09'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('12','13'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('13','14'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('16','18'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
    },
    {
        "uid": "2",
        "name": "Liam Errington",
        "role": "Director",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('08','09'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('16','18'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
   },
    {
        "uid": "1",
        "name": "Rob Errington",
        "role": "CEO",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('08','09'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('13','14'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('14','15'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('18','21'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
    },
    {
        "uid": "8",
        "name": "Samantha Fox",
        "role": "Norks Consultant",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('09','10'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('17','18'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
    },
    {
        "uid": "4",
        "name": "Rich Manley-Reeve",
        "role": "Senior Developer",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('09','10'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('11','12'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('12','13'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('14','15'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('15','16'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('17','19'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
    },
    {
        "uid": "7",
        "name": "Bob Morton",
        "role": "Product Engineer",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('08','09'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            },
            {
                "activity": "EXIT",
                "time_logged": "<?php $r=randtime('16','18'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
    },
   {
        "uid": "5",
        "name": "Peter Rouse",
        "role": "Managing Director",
        "activity_log": [
            {
                "activity": "ENTRY",
                "time_logged": "<?php $r=randtime('08','10'); echo $r[0]; ?>",
                "time": "<?php echo $r[1];?>"
            }
        ]
    },
    {
        "uid": "6",
        "name": "Alphonse Verbacker",
        "role": "Sales Manager"
    }
]
