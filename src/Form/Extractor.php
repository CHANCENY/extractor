<?php

namespace Drupal\extractor\Form;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class Extractor extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'extractor_6547372_544_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    if(!isset($_SESSION['uploaded-file-session'])){
      return $this->fileUploaderForm($form,$form_state);
    }
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if(!isset($_SESSION['uploaded-file-session'])){

    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

     if(!isset($_SESSION['uploaded-file-session'])){
       $values = $form_state->getValues();
       $fid = $values['my_file'][0];
       $database = \Drupal::database();
       $query = $database->query("SELECT * FROM file_managed WHERE fid = {$fid}");
       $result = $query->fetchAll();
       $_SESSION['uploaded-file-session']=$result[0];
     }
//    \Drupal::messenger()->addMessage(t("Student Registration Done!! Registered Values are:"));
//    foreach ($form_state->getValues() as $key => $value) {
//      \Drupal::messenger()->addMessage($key . ': ' . $value);
//    }
  }

  public function fileUploaderForm(array $form, FormStateInterface $form_state){
    $form['file_upload_details'] = array(
      '#markup' => t('<p>In the form below please upload only excel sheets that have xlsx extension otherwise you will get unexpected result</p>'),
    );
    $form['field_set']=array(
      '#type' => 'fieldset',
      '#title' => t('Excel sheet file upload'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    $validators = array(
      'file_validate_extensions' => array('xlsx','XLSX'),
    );
    $form['field_set']['my_file'] = array(
      '#type' => 'managed_file',
      '#name' => 'excel_file',
      '#title' => t('Excel File *'),
      '#size' => 20,
      '#description' => t('excel format only'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://excels/',
    );
    $form['field_set']['actions']['#type'] = 'actions';
    $form['field_set']['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Attach file'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  public function configurationFormSetting(array $form, FormStateInterface $form_state){
     if(isset($_SESSION['uploaded-file-session'])){
       $dataFile = $_SESSION['uploaded-file-session'];
       $form['file_upload_details'] = array(
         '#markup' => t('<p>This form allows you to set fields of content type if content type already exist or to give content type name and<br>
                                  it`s fields names, types, based on the first row data found in uploaded excel sheet</p>'),
       );
       $form['field_set_config']=array(
         '#type' => 'fieldset',
         '#title' => t('Configure your content type'),
         '#collapsible' => TRUE,
         '#collapsed' => FALSE,
       );

       //find data rows

     }
  }

  public function excelXSLXRows($excelPath){
    if ( $xlsx = SimpleXLSX::parse('book.xlsx') ) {
      print_r( $xlsx->rows() );
    } else {
      echo SimpleXLSX::parseError();
    }
  }
}
