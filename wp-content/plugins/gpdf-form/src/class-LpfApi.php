<?php
/**
 * 
 */
class LpfApi
{
	private $url;

	public function __construct()
	{
		$this->url = "https://app.linkpdfform.com/";
	}

//	public function getToken()
//	{
//		$token = get_option( 'lpf_token' );
//		if ($token) {
//			$this->checkTokenValidity($token);
//			return $token;
//		}
//		return $this->generateToken();
//	}

	public function generateToken()
	{
        $user               = wp_get_current_user();
        $data               = [];

        $apiData            = (new LPFAddOn)->get_plugin_settings();
        $data['email']      = $apiData['ApiEmail'];
        $data['app_key']    = $apiData['Apikey'];

        if (empty($data['app_key']) || empty($data['email']))  {
           return lpf_return(false, "Please submit email and API key");
        }
        $LpfCurl            = new LpfRequest;
        $url                = $this->url . "auth/login";
        $method             = "POST";
        $response           = json_decode($LpfCurl->prepareRequest($url, $method, $data));

        if (!$response->success) {
            return lpf_return(false, $response->message);
        }

    //todo: WILL CHECK NEXT VERSION
//        update_option( 'lpf_token', $response->token );

        return lpf_return(true, '', ['token' => $response->token]);

	}

//	public function checkTokenValidity($token)
//	{
//		$data               = [];
//        $data['token']      = $token;
//        $LpfRequest            = new LpfRequest;
//        $url                = $this->url . "auth/validate-token";
//        $method             = "POST";
//        $return             = $LpfRequest->prepareRequest($url, $method, $data);
//        $return             = json_decode($return);
//        if (!isset($return->success)) {
//        	die('Error occurred.');
//        }
//        return;
//	}

    public function getTemplates($token)
    {
        $data               = [];
        $LpfRequest            = new LpfRequest;
        $url                = $this->url . "api/templates";
        $method             = "GET";
        $response           = $LpfRequest->send($url ."?". http_build_query($data), $method, $data, false, true, $token);
        $response           = json_decode($response);
        if (!$response->success) {
            return lpf_return(false, $response->message);
        }

        return lpf_return(true, $response->message, ['templates' => $response->templates]);
    }

    public function getTemplateFields($token, $template_id)
    {
        $LpfAuthorization       = new LpfAuthorization();
        if (!$LpfAuthorization->can(get_current_user_id(), 'edit_posts')){
            return lpf_return(false, 'you do not have authorization');
        }

        $data               = [];
        // $data['token']      = $token;
        $LpfCurl            = new LpfRequest;
        $url                = $this->url . "api/1.0/static/template/{$template_id}/get-field"; // api/1.0/static/template/1/get-field
        $method             = "GET";
        $response           = $LpfCurl->send($url ."?". http_build_query($data), $method, $data, false, true, $token);
        $response           = json_decode($response);
        if (!$response->success) {
            return lpf_return(false, $response->message);
        }

        return lpf_return(true, $response->message, ['fields' => $response->fields]);
    }

    public function sendTemplateFile($abspath)
    {

    }

    public function sendFormFields($entry)
    {
        $templateData         = [];
        $form_id              = $entry['form_id'];

        if (empty($form_id)) {
            return;
        }

        $LpfCurl              = new LpfRequest;
        $LpfFieldsAssociation = new LpfFieldsAssociation();
        $tokenResponse        = $this->generateToken();
        $token                = $tokenResponse['token'];
        $fieldsAssociation    = $LpfFieldsAssociation->getRow(['form_id' => $form_id]);
        $fieldsMap            = unserialize($fieldsAssociation->arr);
        $template_id          = $fieldsAssociation->template_id;

        $i = 0;
        foreach ($fieldsMap as $fieldsId => $fieldsInfo) {
            $templateData[$i]['value']      = $entry[$fieldsId];
            $templateData[$i]['key']        = $fieldsInfo['pdf_field_name'];
            $i++;
        }
        if (empty($template_id) || empty($templateData)) {
            return;
        }



        $data               = ['templateData' => $templateData];
        $url                = $this->url . "api/1.0/static/template/{$template_id}/field-values"; // "api/1.0/static/template/{$template_id}/get-field";
        $method             = "POST";
        $response           = $LpfCurl->send($url, $method, $data, false, true, $token);

        $response           = json_decode($response);

        if (!$response->success) {
            return lpf_return(false, $response->message);
        }

        return lpf_return(true, $response->message, ['fields' => $response->raw_pdf]);
    }
}