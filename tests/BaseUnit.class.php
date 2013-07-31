<?php

class BaseUnit
{
    protected $client = NULL;
    private $current_method = '???';

    public function __construct(){
        $this->client = new Gibson();
        $this->assertTrue( $this->client->pconnect(GIBSON_SOCKET) );
    }

    protected function assert( $x, $message = NULL ){
        if( $x == FALSE ){
            $this->clean();

            $trace = debug_backtrace();

            $caller = array_shift($trace);
            while( $caller['file'] == realpath(__FILE__) && $caller['function'] != '__construct' ){
                $caller = array_shift($trace);
            }

            $file = basename($caller['file']);

            throw new Exception( "{$this->current_method} ( $file:{$caller['line']} )" );
        }
    }

    protected function assertTrue($x){
        $this->assert( $x == TRUE );
    }

    protected function assertFalse($x){
        $this->assert( $x == FALSE );
    }

    protected function assertNull($x){
        $this->assert( $x === NULL );
    }

    protected function assertNotNul($x){
        $this->assert( $x !== NULL );
    }

    protected function assertIsA($x){
        $this->assert( is_array($x) );
    }

    protected function assertIsSet($x,$y){
        $this->assertIsA($x);
        $this->assert( isset($x[$y]) );
    }

    protected function assertNotA($x){
        $this->assert( is_array($x) === FALSE );
    }

    protected function assertEqual( $x, $y ){
        $this->assert( $x === $y ); 
    }

    protected function assertNotEqual( $x, $y ){
        $this->assert( $x !== $y );
    }

    public function run(){
        $methods = get_class_methods($this);
        foreach( $methods as $method ){
            if( strpos( $method, 'test' ) === 0 ){
                $this->current_method = $method;

                $this->$method();
                $this->clean();
            }
        }
    }

    public function clean(){

    }
}

?>