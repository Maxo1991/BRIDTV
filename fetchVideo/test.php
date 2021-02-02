<?php
public function register(){
    $user = $this->Users->newEntity();
    try {
        if($this->request->is('post')) {
        $uniquecode = substr(md5(microtime()),0,10); //generate random string
        $randomKey = substr(md5(microtime()),0,10);
        $this->request->data['otp'] = $uniquecode;
        $getUserEmail = $this->Request->data['email'];
        $user = $this->Users->patchEntity($user,$this->request->data);
        if($this->Users->save($user)){
                $bodyEmail = "You have successfully registered.";
                $bodyEmail .= "To active account please click on below link";
                $aLink = Router::url(array("controller"=>"users","action"=>"activate", $uniquecode, $randomKey),true);
                $bodyEmail .= '<p><br><br><a style="width:50%;color:#fff;text-decoration:none;background:#333;display:block;padding:10px;text-align:center;-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;margin:10px auto " href="'.$aLink.'"> Please verify your email address </a></p>';
                if($this->Main->sendEmail(['to'=>$getUserEmail,'subject'=>'Registration Complete','title'=>'Registration Complete','body'=>$bodyEmail])){
                    $this->Flash->success(__('Your account has been registered. please check your email address to activate your account'));
                    return $this->redirect(['action' => 'register']);
                }else{
                    $this->Flash->error(__('Registration not completed. Please try again.'));
                    return $this->redirect(['action' => 'index']);
                }
            }else{
                $this->Flash->error(__('Unable to register your account.'));
            }
        }
    }catch (\Exception $e) {
        $this->Flash->error($e->getMessage());
        return $this->redirect(['action' => 'register']);
    }
    $this->set('user',$user);
    $this->set('page_title',__('Registeration'));
}

/**
* Activate method
*
* @return \Cake\Network\Response|null
* @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
*/

public function activate($getUniCode='', $randomKey='')
{
if(trim($getUniCode)!="" && $randomKey!="") {
$getUniCode = filter_var($getUniCode, FILTER_SANITIZE_STRING);
$getUser = $this->Users->find('all',['conditions'=> ['otp'=> $getUniCode,'status'=> 0]])->first();
    if($getUser) {
    $getUserId = $getUser->id;
    $updateActivate  = $this->Users->updateAll(['status'=> 1, 'otp'=> ''], ['id'=> $getUserId]);
    $this->Flash->success(__('Your account has been Activated successfully. please login'));
    return $this->redirect(['action' => 'register']);
    }
}


