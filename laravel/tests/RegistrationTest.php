<?php

require_once(__DIR__.'\MainTest.php');

class RegistrationTest extends MainTest{
  public function testRegistrationPage(){
    if(Config::get('applicationConfig.mode') == 'war'){
      $this->login();
      if(!Operation::haveClub()){
        $this->visit('/registration')
             ->press('ก30931')
             ->seePageIs('/comfirmed')
             ->see('ชมรมค้นพบตนเอง');
      }else{
        $this->visit('/registration')
             ->seePageIs('/confirmed');
      }
    }else{
      $response = $this->call('GET', '/registration');
      $this->assertEquals(404, $response->status());
    }
  }

  public function testAddRegistration(){
    if(Config::get('applicationConfig.mode') == 'war'){
      $this->login();
      if(!Operation::haveClub()){
        $this->call('POST', '/registration.do', array(
          'club_code' => 'ก30931'
        ));
      }else{
        $this->visit('/registration')
             ->seePageIs('/confirmed');
      }
    }else{
      $response = $this->call('GET', '/registration');
      $this->assertEquals(404, $response->status());
    }
  }
}
