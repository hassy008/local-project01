<?php
/**
 * 
 */
class LpfHook
{
	
	static public function init()
	{
		$self = new self();

        add_action("admin_init", array($self, 'customMetaBox'));
		add_action( 'gform_after_submission', array($self,'afterSubmission'), 10, 2 );
        add_action('save_post', array($self, 'saveCustomFields'), 10, 3);
        add_action( 'gform_entries_first_column_actions', array($self, 'first_column_actions'), 10, 4 );
	}

    function first_column_actions( $form_id, $field_id, $value, $entry ) {
        $LpfGeneratedPdf = new LpfGeneratedPdf();
        $generatedPdf = $LpfGeneratedPdf->getRow(['entry_id' => $entry['id']]);
//        echo '<pre>';
//        print_r($generatedPdf);
//        echo '</pre>';

//        if (empty($generatedPdf)) {
//            echo '';
//        }


	    $filename = basename($generatedPdf->pdf_path);

        $upload_dir = \wp_upload_dir();

        $upload_base_dir = $upload_dir['baseurl'];
        $upload_path = $upload_base_dir . '/generated_pdf';
        $url = $upload_path.'/'.$filename;

        printf( '| <a  href="%s">View PDF</a>', $url );

    }

    public function saveCustomFields($post_id, $post, $update)
    {
        if ($post->post_type == 'gpdf-form' && is_array($_POST['data']['arr'])) {
            $LpfAuthorization       = new LpfAuthorization();
            if (!$LpfAuthorization->can(get_current_user_id(), 'edit_posts')){
                return false;
            }

			$data                  = [];
			$data['form_id']       = esc_sql(intval($_POST['form_id']));
			$data['post_id']       = $post_id;
			$data['template_id']   = esc_sql(intval($_POST['template_id']));
			$data['arr']           = serialize(lpf_sanitize_array($_POST['data']['arr']));
			$LpfFieldsAssociation  = new LpfFieldsAssociation;
			$response = $LpfFieldsAssociation->updateOrInsert($data, ['form_id' => $data['form_id']]);

        }
    }

    public function customMetaBox()
    {
        add_meta_box('form_fields', 'Form Fields', array($this, 'displayFields'), 'gpdf-form', 'normal', 'high');
    }

    public function displayFields()
    {
        ob_start();
        include_once LPF_VIEW_PATH . "metabox/content-field_association.php";
        $content = ob_get_contents();
        return $content;
    }

	public function afterSubmission( $entry, $form ) 
	{
		$LpfApi    = new LpfApi();
		$res = $LpfApi->sendFormFields($entry);

		if ($res['fields']) {
            $timestamp = date("YmdHis");
//            error_log(print_r($entry, true), 3, LPF_PATH.'sfsfsfsfsf.log');
            $filename = $entry['id'].'_'.$timestamp.".pdf";
            $upload_dir = \wp_upload_dir();
            $upload_base_dir = $upload_dir['basedir'];
            $upload_path = $upload_base_dir . '/generated_pdf';
            if (!is_dir($upload_path)) {
                wp_mkdir_p($upload_path);
            }

            file_put_contents($upload_path.'/'.$filename, base64_decode($res['fields']));

            $data = [];
            $data['form_id'] = $entry['form_id'];
            $data['entry_id'] = $entry['id'];
            $data['pdf_path'] = $upload_path.'/'.$filename;

            $LpfGeneratedPdf = new LpfGeneratedPdf();
            $isInserted = $LpfGeneratedPdf->insert($data);

        }
    }
}