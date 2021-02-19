<?php
$servername = "localhost";
$username = "id14786519_ayomadewale";
$password = "Qwerty@1234@";
$db = "id14786519_stackjobs";
$conn = mysqli_connect($servername,$username,$password,$db);

if(!$conn){
    die("Hello, Couldn't connect to database");
}


$beginQuery = "SELECT `begin_scrap` FROM begin_scrap WHERE id = 1 LIMIT 1";
$rsp = mysqli_query($conn, $beginQuery);
$begin = mysqli_fetch_row($rsp)[0];





loopStacks($begin,$conn);

function loopStacks($begin, $conn)
{
    $ends = 0;
    for ($i=0; $i < 10; $i++) {
        $val = $begin + $i;
        $ends = $val;
        $base = "https://stackoverflow.com/jobs/";
        $url = $base.$val;

        print_r(getStackoverflowJob($url,$conn));
        
    }
    $beginQuery = "UPDATE begin_scrap SET begin_scrap = $ends WHERE id = 1";
    mysqli_query($conn, $beginQuery);
}

function getStackoverflowJob($url,$conn)
{
   
        $contents = @file_get_contents($url);
            $job = NULL;
            $regex = '!<a href="\/jobs\/(.*)" title="(.*)" class="fc-black-900">(.*?)<\/a>!';
            $regex2 = '!<a class="fc-black-700" href="\/jobs\/(.*)">(.*?)<\/a>!';
            $regex3 = '/<section class="mb32 fs-body2 fc-medium pr48">.*? <\/section/ms';
            $regex4 = '/<span class="fw-bold">.*?<\/span>/ms';
            $regex5 = '/<span class="-salary pr16" title=".*?">.*?<\/span>/ms';
    
    
    
            preg_match_all($regex,$contents,$name);
            if (!empty($name) and !empty($name[3])) {
                $job["name"] =strip_tags(str_replace(["\n",'\t','\r','\/'], "",$name[3][0]));
            }else{
                $job["name"] = "";
            }
            preg_match_all($regex2,$contents,$company);
            if (!empty($company) and !empty($company[2])) {
                $job["company"] = strip_tags(str_replace(["\n",'\t','\r','\/'], "",$company[2][0]));
            }else{
                $job["company"] = "";
            }
            
            preg_match_all($regex4,$contents,$jobType);
            if (!empty($jobType) and !empty($jobType[0])) {
                $job["job_type"] = strip_tags(str_replace(["\n",'\t','\r','\/'], "",$jobType[0][0]));
            }else{
                $job["job_type"] = "";
            }
            preg_match_all($regex5,$contents,$salary);
            if (!empty($salary) and !empty($salary[0])) {
                $job["salary"] = strip_tags(str_replace(["\n",'\t','\r','\/'], "",$salary[0][0]));
            }else{
                $job["salary"] = "";
            }
            preg_match_all($regex3,$contents,$desc);
            if (!empty($desc) and !empty($desc[0])) {
                $job["description"] = strip_tags(str_replace(["\n",'\t','\r','\/'], "",strip_tags($desc[0][0])));
            }else{
                $job["description"] = "";
            }


            $jobname = $job['name'];
            $jobcompany = $job["company"];
            $job_type = $job["job_type"];
            $jobsalary = $job["salary"];
            $jobdesc = $job["description"];
            
            $jobQuery = "INSERT INTO `jobs` (`id`, `name`, `company`, `job_type`, `salary`, `description`) 
            VALUES (NULL,'$jobname','$jobcompany','$job_type','$jobsalary','$jobdesc')";
            if($jobname != ''){
                return mysqli_query($conn, $jobQuery);
            }else{
                return;
            }
            
       
    
     

}

?>