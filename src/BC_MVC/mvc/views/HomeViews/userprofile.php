<!-- main -->
<main class="container">
    
    <div class="row">
      <div class="col-md-3">
        <div class="panel panel-default">
          <div class="panel-body">
          <h4>Request</h4>
          <?php
              if($data["result"]["is_already_friends"])
              {
                  echo '<a class="btn btn-success" role="button" href="/Facebook_MVC/Home/Functions/unfriend_req/'.$data["result"]["user_data"]->id.'" class="req_actionBtn unfriend">Unfriend</a>';
              }
              elseif ($data["result"]["check_req_sender"]) {
                  echo '<a class="btn btn-success" role="button" href="/Facebook_MVC/Home/Functions/cancel_req/'.$data["result"]["user_data"]->id.'" class="req_actionBtn cancleRequest">Cancel Request</a>';
              }
              elseif ($data["result"]["check_req_receiver"]) {
                  echo '<a class="btn btn-success" role="button" href="/Facebook_MVC/Home/Functions/ignore_req/'.$data["result"]["user_data"]->id.'" class="req_actionBtn ignoreRequest">Ignore</a>&nbsp;
                      <a class="btn btn-success" role="button" href="/Facebook_MVC/Home/Functions/accept_req/'.$data["result"]["user_data"]->id.'" class="req_actionBtn acceptRequest">Accept</a>';
              }
              else {
                  echo '<a class="btn btn-success" role="button" href="/Facebook_MVC/Home/Functions/send_req/'.$data["result"]["user_data"]->id.'" class="req_actionBtn sendRequest">Send Request</a>';
              }
          
          
          ?>
          </div>
        </div>
      </div>
      <div class="col-md-6 offset-col-md-3">
        <!-- user profile -->
        <div class="media">
          <div class="media-left">
            <?php
            echo '<img src="'.PATH.'img/'.$data["result"]["user_data"]->user_image.'" class="media-object" style="width: 128px; height: 128px;">'
            ?>
          </div>
          <div class="media-body">
            <h2 class="media-heading"><?php echo $data["result"]["user_data"]->username?></h2>
          </div>
        </div>
        <!-- user profile -->

        <hr>

        <!-- timeline -->
        <div>
          <!-- post -->
          <div class="panel panel-default">
            <div class="panel-body">
             <?php
              if($data["result"]["is_already_friends"]){
                if($data["result"]["get_all_posts_of_user"]) {
                  foreach($data["result"]["get_all_posts_of_user"] as $post) {
                    echo '<div class="posts" id="post_'. $post->post_id .'">';
                    echo  '<div id="posts-area-' . $post->post_id . '" class="panel-body">';
                    if($post->post_image !== NULL) {
                      echo    '<div class="image-box" id="image_'.$post->post_image_id.'" style="margin:0 auto;">';
                      echo      '<img class="img-responsive image-content" src="data:image/jpg;charset=utf8;base64,' . base64_encode($post->post_image) .'" class="img-rounded" id="Panel_Image" style="width: 250px; height: 230px;margin: 0 auto">';
                      echo    '</div>';
                    }
                    echo    '<div class="message-box"  id="message_' . $post->post_id . '">';
                    echo       '<div class="message-content">' . $post->post_message . '</div>';
                    echo    '</div>';
                    echo  '</div>';
                    echo  '<div class="panel-footer">';
                    echo    '<span>posted by '. $post->username .'</span>';
                    echo  '</div>';
                    
                    
                  }
                }
              }
              else {
                echo    '<div class="message-box"  id="message">';
                echo       '<div class="message-content"><h4> Be friend to each other to see my post!!!</h4></div>';
                echo    '</div>';
              }      
             
             
             
             ?>
            
            </div>
          </div>
          <!-- ./post -->
        </div>
        <!-- ./timeline -->
      </div>
      
    </div>
    
      </div>
      <div class="col-md-3">
        <!-- friends -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Friends</h4>
            <ul>
              <?php
                if ($data["result"]["get_all_friends"]) {
                  foreach($data["result"]["get_all_friends"] as $row) {
                    echo '<li>';
                    echo '<a href="#">'.$row->username.'</a><br>';
                    echo '<a href="/Facebook_MVC/Home/UserProfile/'.$row->id.'" class="btn btn-info" role="button">See profile</a>';
                    echo '</li>';
                  }
                }
                else {
                  echo '<h4>There is no user!</h4>';
                }
              ?>
            </ul>
          </div>
        </div>
        <!-- ./friends -->
  </main>
  <!-- ./main -->