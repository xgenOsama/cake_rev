<?php
App::uses('AppController','Controller');

  class UsersController extends AppController{
    public $components = array('Auth', 'Paginator');
    public function beforeFilter(){
      parent::beforeFilter();
      $this->Auth->allow('add', 'logout','login');
    }

    public function login() {
      if ($this->request->is('post')) {
          if ($this->Auth->login($this->request->data)) {
              return $this->redirect($this->Auth->redirectUrl());
          }
          $this->Flash->error(__('Invalid username or password, try again'));
      }
    }

    public function logout() {
      return $this->redirect($this->Auth->logout());
    }

    public function index(){
      //$this->User->recursive(0);
      $this->set('users',$this->Paginator->paginate());
    }


    public function view($id = null){
      $this->User->id = $id ;
      if (!$this->User->exists()){
        throw new Exception(__("Invalid User"));
      }
      $this->set('user',$this->User->findById($id));
    }

    // public function view($id = null){
    //   $this->User->id = $id;
    //   if(!$this->User->exists()){
    //     throw new Exception(__("Invalid User"));
    //   }
    //   $this->set('user',$this->User->findById($id));
    // }
    public function add($id = null){
      if($this->request->is('post')){
        $this->User->create();
        if($this->User->save($this->request->data)){
          $this->Flash->success(__('The user has been saved'));
          return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('The user could not be saved. Please, try again.'));
      }
    }

    public function edit($id = null){
      $this->User->id = $id;
      if(!$this->User->exists()){
        throw new Exception(__('Invalid User'));
      }
      if($this->request->id('post') || $this->request->is('put')){
        if($this->User->save($this->request->data)){
          $this->Flash->success(__('The User has been saved'));
          return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('The user can not be saved please try again'));
        return $this->redirect(array('action' => 'index'));
      }else{
        $this->request->data = $this->User->findById($id);
        unset($this->request->data['User']['password']);
      }
    }
    public function delete($id = null){
      $this->request->allowMethod('post');
      $this->User->id = $id;
      if(!$this->User->exists()){
        throw new NotFoundException(__('Invalid user'));
      }
      if($this->User->delete()){
        $this->Flash->success(__('User deleted succesfuly'));
        return $this->redirect(array('action' => 'index'));
      }
      $this->Flash->error(__('User deos not deleted'));
      return $this->redirect(array('action' => 'index'));
    }
  }
 ?>
