<?php

require_once(__DIR__.'\MainTest.php');

class AuditionTest extends MainTest{
  public function testAuditionPage(){
    if(Config::get('applicationConfig.mode') == 'audition'){
      $this->login();
      if(!Operation::haveClub()){
        if(!Operation::havePendingAudition()){
          $this->visit('/audition')
               ->press('ก30927')
               ->seePageIs('/audition')
               ->see('ยกเลิก');
        }else{
          $this->visit('/audition')
               ->press('ยกเลิก')
               ->seePageIs('/audition')
               ->dontSee('ยกเลิก');
        }
      }
    }else{
      $response = $this->call('GET', '/audition');
      $this->assertEquals(404, $response->status());
    }
  }

  public function testAddAudition(){
    if(Config::get('applicationConfig.mode') == 'audition'){
      $this->login();
      if(!Operation::havePendingAudition() && !Operation::haveClub()){
        $this->call('POST', '/audition.do', array(
          'club_code' => 'ก30927'
        ))
             ->seePageIs('/audition')
             ->see('ยกเลิก');
      }
    }else{
      $response = $this->call('POST', '/audition.do');
      $this->assertEquals(302, $response->status());
    }
  }

  public function testRemoveAudition(){
    if(Config::get('applicationConfig.mode') == 'audition'){
      $this->login();
      if(Operation::havePendingAudition() && !Operation::haveClub()){
        $this->call('POST', '/audition.delete', array(
          'club_code' => 'ก30927'
        ));
      }
    }else{
      $response = $this->call('POST', '/audition.delete');
      $this->assertEquals(302, $response->status());
    }
  }
}
