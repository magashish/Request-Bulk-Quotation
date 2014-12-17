<?php
class Rvtech_Quotepopup_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
            $this->loadLayout();
            $this->renderLayout();
    }
    public function saveAction()
    {
      if($mydata = Mage::app()->getRequest()->getPost()){
        
        /** *******************************send email *******************************************************/
          $emailTemplate = Mage::getModel('core/email_template')->loadDefault('quotepopup_email_template');

          /* Sender Name */
          $senderName = Mage::getStoreConfig('trans_email/ident_sales/name'); 
          /* Sender Email */
          $senderEmail = Mage::getStoreConfig('trans_email/ident_sales/email');

          //Getting the Store General E-Mail.
          $customerEmail = $mydata['email'];
          $subject = $mydata['subject'];
          $customerName = $mydata['name'];

          //Variables for Confirmation Mail.
          $emailTemplateVariables = array();
          
          $emailTemplateVariables['name'] = $mydata['name'];
          $emailTemplateVariables['email'] = $mydata['email'];
          $emailTemplateVariables['organization'] = $mydata['organization'];
          $emailTemplateVariables['phone'] = $mydata['phone'];
          $emailTemplateVariables['fax'] = $mydata['fax'];
          $emailTemplateVariables['size'] = $mydata['size'];
          $emailTemplateVariables['message'] = $mydata['message'];
        
          //Appending the Custom Variables to Template.
          $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

          //Sending E-Mail to Customers.
          $mail1 = Mage::getModel('core/email')
           ->setToName($customerName)
           ->setToEmail($customerEmail)
           ->setBody($processedTemplate)
           ->setSubject($subject)
           ->setFromEmail($senderEmail)
           ->setFromName($senderName)
           ->setType('html');
           try{
           //Confimation E-Mail Send
             $mail1->send();
           }
            catch(Exception $error)
           {
              Mage::getSingleton('core/session')->addError($error->getMessage());
              return false;
           }

          //Send Email to Sales representative.
          $mail2 = Mage::getModel('core/email')
           ->setToName($senderName)
           ->setToEmail($senderEmail)
           ->setBody($processedTemplate)
           ->setSubject($subject)
           ->setFromEmail($customerEmail)
           ->setFromName($customerName)
           ->setType('html');
           try{
           //Confimation E-Mail Send
             $mail2->send();
           }
            catch(Exception $error)
           {
              Mage::getSingleton('core/session')->addError($error->getMessage());
              return false;
           }

       /** *******************************send email **************/
       echo 'success';
      }
  }
   
}

