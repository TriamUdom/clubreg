<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

class MainTest extends TestCase{

    /**
     * Index page tester
     *
     * @return void
     */
    public function testIndexPage(){
        $this->visit('/')
             ->see('ดำเนินการต่อ');
    }

    /**
     * Login page tester
     *
     * @return void
     */
    public function testLoginPage(){
        $this->visit('/login')
             ->see('เข้าสู่ระบบ');
    }

    /**
     * Test logging in process
     *
     * @return void
     */
     /*
    public function testLogin(){
      if(Config::get('applicationConfig.mode') == 'confirmation'){
        $this->visit('/login')
             ->type('12345', 'sid')
             ->type('1111111111119', 'nid')
             ->press('เข้าสู่ระบบ')
             ->seePageIs('/confirm');
      }else if(Config::get('applicationConfig.mode') == 'audition'){
        $this->visit('/login')
             ->type('12345', 'sid')
             ->type('1111111111119', 'nid')
             ->press('เข้าสู่ระบบ')
             ->seePageIs('/audition');
      }else if(Config::get('applicationConfig.mode') == 'war'){
        $this->visit('/login')
             ->type('12345', 'sid')
             ->type('1111111111119', 'nid')
             ->press('เข้าสู่ระบบ')
             ->seePageIs('/registration');
      }
    }*/

    /*
    public function testOperation(){
      if(Config::get('applicationConfig.mode') == 'confirmation'){
        if(Operation::userLoggedIn()){
          $this->visit('/comfirm')
               ->see('ยืนยันการลงทะเบียนชมรมเดิม');
        }else{
          $this->login();
          $this->visit('/confirm')
               ->seePageIs('/confirm');
        }
      }else if(Config::get('applicationConfig.mode') == 'audition'){
        if(Operation::userLoggedIn()){
          $this->visit('/audition')
               ->see('');
        }else{

        }
      }else if(Config::get('applicationConfig.mode') == 'war'){

      }
    }*/

    public function login(){
      $this->visit('/login')
           ->type('12345', 'sid')
           ->type('1111111111119', 'nid')
           ->press('เข้าสู่ระบบ');
    }
}
