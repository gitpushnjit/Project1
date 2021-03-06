<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Manage {
//autiload is capability of loading and linking portions of a program from mass storage automatically when needed, so that it is not required to define or include those portions of the program explicitly.
    public static function autoload($class) {

        include $class . '.php';
    }

}

spl_autoload_register(array('Manage', 'autoload'));

//object declaration
$obj = new main();

class main {

    public function __construct() {
        $pageRequest = 'homepage';

//isset() function is used to check whether a variable is set or not.
//$_REQUEST is used to collect data after submitting an HTML form.
        if (isset($_REQUEST['page'])) {
            $pageRequest = $_REQUEST['page'];
        }

        $page = new $pageRequest;
//$_SERVER[REQUEST_METHOD] returns the request method used to access the page 
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }
    }

}

abstract class page {

    protected $html;
//__construct() is the method name for the constructor. 
    public function __construct() {
        $this->html .= '<html>';
//declaring a css file
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }

    public function __destruct() {
        $this->html .= '</body></html>';
        stringFunctions::myPrint($this->html);
    }

    public function get() {
        echo 'default get message';
    }

//$_POST are used to collect form-data.
    public function post() {
        print_r($_POST);
    }

}

class homepage extends page {
//Creating headings like Upload Form and Upload button
    public function get() {
        $form = '<form method="post" enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
        $form .= '<input type="submit" value="Upload" name="submit">';
        $form .= '</form> ';
        $this->html .= '<h1>Upload Form</h1>';
        $this->html .= $form;
    }

    public function post() {

        $name = $_FILES['fileToUpload']['name'];
        $temp_name = $_FILES['fileToUpload']['tmp_name'];
        if (isset($name)) {
//Declaring path for upload
            $location = '/afs/cad.njit.edu/u/p/g/pg395/public_html/UPLOADS/';
            $upload_file_path = $location . $name;

            $table = new htmlTable();

            if (move_uploaded_file($temp_name, $upload_file_path)) {
                $table->print_table($upload_file_path);
            }
//Check if the file is uploaded.            
        } else {
            echo 'You should select a file to upload !!';
        }
    }

}

//html table syntax.
class htmlTable extends page {

    protected function print_header($cell) {
        $this->html .= "<th>";
        $this->html .= htmlspecialchars($cell);
        $this->html .= "</th>";
    }

    protected function print_row($cell) {
        $this->html .= "<td>";
        $this->html .= htmlspecialchars($cell);
        $this->html .= "</td>";
    }

    protected function print_line_by_line($f, $flag) {
        while (($line = fgetcsv($f)) !== false) {

            $this->html .= "<tr>";

            foreach ($line as $cell) {
                if ($flag) {
                    $this->print_header($cell);
                    
                } else {
                    $this->print_row($cell);
                }
            }
            $flag = false;
            $this->html .= "</tr>";
        }
    }
//display table using html.
    public function print_table($path) {
        $this->html .= '<html><body><table border = "1">';
        if (file_exists($path)) {
            $f = fopen($path, "r");
            $flag = true;
            $this->print_line_by_line($f, $flag);
            fclose($f);
        }
        $this->html .= "\n</table></body></html>";
    }

}

class stringFunctions {

    static public function myPrint($inputText) {
        return print($inputText);
    }

    static public function myStringLen($text) {
        return strLen($text);
    }

}
?>
     