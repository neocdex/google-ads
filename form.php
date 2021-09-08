<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Keywords Form</title>
        <link href="minimal-table.css" rel="stylesheet" type="text/css" />
    </head>
<?php
require_once 'GenerateKeywordIdeas.php';

use Google\Ads\GoogleAds\Examples\Planning\GenerateKeywordIdeas;

class KeywordForm
{
    private $keywords;
    private $errors;

    function __construct($post)
    {
        $this->errors = array();
        if(isset($post['keyword-list']))
        {
            $this->keywords = explode(",", $post['keyword-list']);
        }
        else
        {
            array_push($this->errors, "Error parsing keywords");
        }
    }
    function getKeywords()
    {
        return $this->keywords;
    }
    function getErrors()
    {
        return $this->errors;
    }
    function isValid()
    {
        return true;
    }
}

$errors = array();
if($_SERVER['REQUEST_METHOD']=='POST')
{
    //new form
    $form = new KeywordForm($_POST);
    //validate form
    if($form->isValid())
    {
        //get keywords resultset
        $resultSet = GenerateKeywordIdeas::main($form->getKeywords());
        //show data in table
        $table = "<div class='backbtn'><a class='searchlink' href='javascript:history.back()'>Search again</a></div>";
        $table .= "<h2>Results: <span style='font-size: 1.2rem;'>".count($resultSet)." records.</span></h2>";        
        $table .= "<table><tr><th>No.</th><th>Keyword</th><th>Avg monthly searches</th></tr>";//<th>Competition</th></tr>";
        $count = 1;
        foreach($resultSet as $key => $value)
        {
            $table .= "<tr><td>".$count."</td><td>".$key."</td>";
            $table .= "<td>".$value[0]."</td></tr>";//<td>".$value[1]."</td></tr>";
            $count++;
        }
        $table .= "</table>";
        print($table);
        exit;
    }
    else
    {
        //show errors
        $errors = $form->getErrors();
        print_r($errors);
    }
}
?>
    <body>
        <div class="container">
            <header>Keywords form</header>
            <section>
                <form name="keyword-form" method="post" action="form.php" >
                    <div class="row">
                        <div class="col-25">
                            <label for="keywords-list">Keywords separeted by comma:</label>
                        </div>
                        <div class="col-75">
                            <textarea name="keyword-list"></textarea>
                        </div>
                    </div>
                    <div class="row">                        
                        <input type="submit" value="Submit" />
                        <input type="reset" value="Clear" />    
                    </div>
                </form>
            </section>
        </div>
    </body>
</html>