<?php
/*
 * ���������ļ�������API Key, Secret Key���Լ�����������õ�API�б�
 * This file for configure all necessary things for invoke, including API Key, Secret Key, and all APIs list
 *
 * @Modified by mike on 17:54 2011/12/21.
 * @Modified by Edison tsai on 16:34 2011/01/13 for remove call_id & session_key in all parameters.
 * @Created: 17:21:04 2010/11/23
 * @Author:     Edison tsai<dnsing@gmail.com>
 * @Blog:       http://www.timescode.com
 * @Link:       http://www.dianboom.com
 */

$config                         = new stdClass;

$config->APIURL         = 'http://api.renren.com/restserver.do'; //RenRen����API���õ�ַ������Ҫ�޸�
$config->APPID          = '205536';      //���API Key������������
$config->APIKey         = '554f0b79093642918f322e31ced90960';   //���API Key������������
$config->SecretKey      = '7d8492648f044ec6a7eb1141be77dd27';   //���API ��Կ
$config->APIVersion     = '1.0';        //��ǰAPI�İ汾�ţ�����Ҫ�޸�
$config->decodeFormat   = 'json';       //Ĭ�ϵķ��ظ�ʽ������ʵ������޸ģ�֧�֣�json,xml

$config->redirecturi= 'http://127.0.0.1/SNS/sns_authorize/renren_authorize';//��Ļ�ȡcode�Ļص���ַ��Ҳ��accesstoken�Ļص���ַ
$config->scope='publish_feed,status_update,photo_upload,read_user_feed,read_user_feed,read_user_status';

?>