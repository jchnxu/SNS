<?php

        ob_start();
        //echo @$this->uploadhandler;
        echo 'shit2';
        $string = ob_get_contents();
        ob_end_clean();
        file_put_contents('/Users/jchnxu/Development/sites/sns/upload/sns.log', $string);
        return;
