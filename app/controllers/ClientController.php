<?php

use Locker\Repository\User\UserRepository as User;
use Locker\Repository\Lrs\LrsRepository as Lrs;
use Locker\Repository\Client\ClientRepository as Client; //this is currently generating an error - 
//Illuminate \ Container \ BindingResolutionException
//Target [Locker\Repository\Client\ClientRepository] is not instantiable.

class ClientController extends BaseController {

  /**
  * User
  */
  protected $user;

  /**
   * Lrs
   **/
  protected $lrs;
  
  /**
   * Client
   **/
  protected $client;
  

  /**
   * Construct
   *
   * @param User $user
   * @param Lrs $lrs
   * @param Client $client
   */
  public function __construct(User $user, Lrs $lrs, Client $client){

    $this->user = $user;
    $this->lrs  = $lrs;
	$this->client  = $client;
    $this->logged_in_user = Auth::user();
    
  }
  
  /**
   * Load the manage clients page
   *
   * @param  int  $id
   * @return View
   */
  public function manage($id){
  	
     $lrs    = $this->lrs->find( $id );
     $lrs_list = $this->lrs->all(); 
	
	 $clients = \Client::where('lrs_id', $lrs->id)->get();
	 	  
	 
     return View::make('partials.client.manage', array('clients'    => $clients,
						                        'lrs'           => $lrs,
						                        'list'          => $lrs_list
												));
  }
  
   /**
   * Load the manage clients page
   *
   * @param  int  $lrs_id
   * @param  int  $id
   * @return View
   */
  public function edit($lrs_id, $id){
  	
     $lrs    = $this->lrs->find( $lrs_id );
     $lrs_list = $this->lrs->all(); 
	
	 $client = $this->client->find( $id );
	 	  
	 
     return View::make('partials.client.edit', array('client'    => $client,
						                        'lrs'           => $lrs,
						                        'list'          => $lrs_list
												));
  }
  
    /**
   * Create a new client
   *
   * @param  int  $id
   * @return View
   **/

  public function create($id){

	$lrs = $this->lrs->find( $id );
	
	$data = array('lrs_id' => $lrs->id);
	
    if( $this->client->create( $data ) ){
      $message_type = 'success';
      $message      = Lang::get('update_key');
    }else{
      $message_type = 'error';
      $message      = Lang::get('update_key_error');
    }
    
    return Redirect::back()->with($message_type, $message);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $lrs_id
   * @param  int  $id
   * @return View
   */
  public function destroy($lrs_id, $id){

	if( $this->client->delete($id) ){
      $message_type = 'success';
      $message      = Lang::get('delete_client_success');
    }else{
      $message_type = 'error';
      $message      = Lang::get('delete_client_error');
    }
	
   return Redirect::back()->with($message_type, $message);

  }

}