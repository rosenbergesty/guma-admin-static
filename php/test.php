<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Cache-Control, X-Requested-With");

  require_once('connect.php');
  require_once('lib/SimpleExcel/SimpleExcel.php');
  use SimpleExcel\SimpleExcel;
  use SimpleExcel\Spreadsheet\Worksheet;

  try{
    if(!isset($_FILES['upfile']['error']) || is_array($_FILES['upfile']['error'])) {
      throw new RuntimeException('Invalid parameters.');
    }

    switch($_FILES['upfile']['error']){
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new RuntimeException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new RuntimeException('Exceeded filesize limit.');
      default:
        throw new RuntimeException('Unknown errors.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimes = array('text/csv');
    if(!in_array($_FILES['upfile']['type'],$mimes)){
      throw new RuntimeException('Invalid file format');
    }

    $target = 'uploads/';
    $target_file = $target.basename($_FILES['upfile']['name']);
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_file)){

      $excel = new SimpleExcel('CSV');
      $excel->parser->loadFile($target.$_FILES['upfile']['name']);
      $arr = $excel->parser->getField();

      $addressCol = array_search('address', array_map('strtolower', $arr[0]));
      $driverIdCol = array_search('type', array_map('strtolower', $arr[0])) ?: 2;
    }

  } catch (RuntimeException $e){

  }

  echo json_encode($driverIdCol);
?>