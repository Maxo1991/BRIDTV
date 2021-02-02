<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Mailer\Transport\DebugTransport;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public $components = array("Main");

    /**
     * Register method
     *
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Main');
    }

    public function register(){
        $user = $this->Users->newEntity();
        try {
            if($this->request->is('post')) {
                $uniquecode = substr(md5(microtime()),0,10); //generate random string
                $randomKey = substr(md5(microtime()),0,10);
                $this->request->data['status'] = 0;
                $this->request->data['otp'] = $uniquecode;
                $getUserEmail = $this->request->data['email'];
                $user['status'] = $this->request->data['status'];
                $user['otp'] = $this->request->data['otp'];
                $user = $this->Users->patchEntity($user,$this->request->data);
                if($this->Users->save($user)){
                    $bodyEmail = "You have successfully registered.";
                    $bodyEmail .= "To active account please click on below link";
                    $aLink = Router::url(array("controller"=>"users","action"=>"activate", $uniquecode, $randomKey),true);
                    $bodyEmail .= '<p><br><br><a style="width:50%;color:#fff;text-decoration:none;background:#333;display:block;padding:10px;text-align:center;-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;margin:10px auto " href="'.$aLink.'"> Please verify your email address </a></p>';
                    try{
                        $mail = $this->Main->sendEmail(['to'=>$getUserEmail,'subject'=>'Registration Complete','title'=>'Registration Complete','body'=>$bodyEmail]);
                    } catch (Exception $e){
                        echo "Email could not be sent";
                    }
                    $this->Flash->success(__('Your account has been registered. Check your email in a few moments and please activate your account'));
                    return $this->redirect(['action' => 'register']);
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
        if (trim($getUniCode) != "" && $randomKey != "") {
            $getUniCode = filter_var($getUniCode, FILTER_SANITIZE_STRING);
            $getUser = $this->Users->find('all', ['conditions' => ['otp' => $getUniCode, 'status' => 0]])->first();
            if ($getUser) {
                $getUserId = $getUser->id;
                $updateActivate = $this->Users->updateAll(['status' => 1, 'otp' => ''], ['id' => $getUserId]);
                $this->Flash->success(__('Your account has been Activated successfully. please login'));
                return $this->redirect(['action' => 'register']);
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['controller'=> 'videos', 'action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //Login
    public function login(){
        if($this->request->is('post')){
            $user = $this->Auth->identify();
            if($user){
                $this->Auth->setUser($user);
                if($user['status'] == 0){
                    $this->Flash->error(__('The user not activate!'));
                }else{
                    return $this->redirect('/videos/index');
                }
            } else {
                //Bad login
                $this->Flash->error('Incorrect Login');
            }
        }else{
            $this->request->session()->delete('Flash');
        }
    }

    //Logout
    public function logout(){
        $this->Flash->success('You are logged out!');
        return $this->redirect($this->Auth->logout());
    }

//    //Register
//    public function register(){
//        $user = $this->Users->newEntity();
//        if($this->request->is('post')){
//            $user = $this->Users->patchEntity($user, $this->request->data);
//            if($this->Users->save($user)){
//                $this->Flash->success('You are register and you must activate link on your email!');
//
//                $email = new Email();
//                $transport = new DebugTransport();
//                $email->setTransport($transport);
//                $email->setProfile('default');
//                $to = $this->request->data['email'];
//                $email->setFrom(['maksoldinjo8@gmail.com' => 'My Site'])
//                    ->setTo($to)
//                    ->setSubject('Fetch Video Registration')
//                    ->send($to . ' activate link!    https://wwww.google.rs');
//
//                return $this->redirect(['action' => 'login']);
//            }else{
//                $this->Flash->error('You are not register!');
//            }
//        }
//        $this->set(compact('user'));
//        $this->set('_serialize', ['user']);
//    }

    public function beforeFilter(Event $event)
    {
//        $this->Auth->allow(['register']);
        $this->Auth->allow(['register','activate']);
    }
}
