<?php

function rpcQuery ($method,$params,$timeout=array("send"=>0,"receive"=>10))
{
        //returns an associative array
        //$reult["r"] contains the result in *decoded* JSON
        //$result["e"] contains the error, or NULL if there is no error. This could be Bitcoin errors or rpcQuery errors.
 
        //I don't expect all possible errors to be caught. After running this, you should check that it's
        //returning reasonable data.
        
        $user="u";
        $password="p";
        $id=8284;
        $target="127.0.0.1";
        $port=38888;
 
        //construct query
        $query=(object)array("method"=>$method,"params"=>$params,"id"=>$id);
        $query=json_encode($query);
        $auth=base64_encode($user.":".$password);
        $query=$query."\r\n";
        $length=strlen($query);
 
        $in="POST / HTTP/1.1\r\n";
        $in.="Connection: close\r\n";
        $in.="Content-Length: $length\r\n";
        $in.="Host: \r\n";
        $in.="Content-type: text/plain\r\n";
        $in.="Authorization: Basic $auth\r\n";
        $in.="\r\n";
        $in.=$query;
        $offset = 0;
        $len=strlen($in);
        
        //create connection
        $socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        
        //timeouts
        if($timeout["send"]>0)
        {
                socket_set_option($socket,SOL_SOCKET, SO_SNDTIMEO, array("sec"=>$timeout["send"],"usec"=>0));
        }
        if($timeout["receive"]>0)
        {
                socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>$timeout["send"],"usec"=>0));
        }
        
        if(socket_connect($socket,$target,$port)===false)
        {
                $errorcode = socket_last_error();
                //error_log("JSON: Socket error $errorcode: ".__LINE__);
                $error = socket_strerror($errorcode);
                goto returnResult;
        }
        
        while ($offset < $len)
        {
                $sent = socket_write($socket, substr($in, $offset), $len-$offset);
                if ($sent === false) {
                        break;
                }
                $offset += $sent;
        }
        //did all of our data get out?
        if ($offset < $len) 
        {
                $errorcode = socket_last_error();
                //error_log("JSON: Socket error $errorcode: ".__LINE__);
                
                //poorly-named timeout error
                if($errorcode==11)
                {
                        $error="Socket write timed out";
                }
                else
                {
                        $error = socket_strerror($errorcode);
                }
                goto returnResult;
        }
 
        $reply = "";
        do
        {
                $recv = "";
                $recv = socket_read($socket, '1400');
                if($recv===false)
                {
                        $errorcode = socket_last_error();
                        //error_log("JSON: Socket error $errorcode: ".__LINE__);
                        
                        //poorly-named timeout error
                        if($errorcode==11)
                        {
                                $error="Socket read timed out";
                        }
                        else
                        {
                                $error = socket_strerror($errorcode);
                        }
                        goto returnResult;
                }
                if($recv != "")
                {
                        $reply .= $recv;
                }
        }
        while($recv != "");
        
        //socket no longer needed -- close
        socket_shutdown($socket);
        socket_close($socket);
        
        $result=strpos($reply,"\r\n\r\n");
        if($result===false)
        {
                $error="Could not parse result.";
        }
        $result=trim(substr($reply,$result+4));
        
        //construct final array
        returnResult:
        {
                $return=array("r"=>NULL,"e"=>NULL);
                if(isset($error))
                {
                        $return["e"]=$error;
                        return $return;
                }
                $result=json_decode($result,false,512);
                if($result==NULL||!is_object($result))
                {
                        $return["e"]="Decode failed.";
                        return $return;
                }
                if($result->id!=$id)
                {
                        $return["e"]="Wrong ID.";
                        return $return;
                }
                $return["r"]=$result->result;
                $return["e"]=$result->error;
                
                return $return;
        }
}
?>