<?php
loopStacks();

function loopStacks()
{
    $begin = 421944;
    for ($i=0; $i < 10; $i++) {
        
        $val = $begin + $i;
        $base = "https://stackoverflow.com/jobs/";
        $url = $base.$val;
        if(getStackoverflowJob($url) != null){
            echo json_encode(getStackoverflowJob($url));
        }
        
    }
}

function getStackoverflowJob($url)
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
                $job["name"] = str_replace(["\n",'\t','\r'], "",$name[3][0]);
            }
            preg_match_all($regex2,$contents,$company);
            if (!empty($company) and !empty($company[2])) {
                $job["company"] = str_replace(["\n",'\t','\r'], "",$company[2][0]);
            }
            
            preg_match_all($regex4,$contents,$jobType);
            if (!empty($jobType) and !empty($jobType[0])) {
                $job["job_type"] = str_replace(["\n",'\t','\r'], "",$jobType[0][0]);
            }
            preg_match_all($regex5,$contents,$salary);
            if (!empty($salary) and !empty($salary[0])) {
                $job["salary"] = str_replace(["\n",'\t','\r'], "",$salary[0][0]);
            }
            preg_match_all($regex3,$contents,$desc);
            if (!empty($desc) and !empty($desc[0])) {
                $job["description"] = str_replace(["\n",'\t','\r'], "",strip_tags($desc[0][0]));
            }
    
            return $job;
       
    
     

}




?>