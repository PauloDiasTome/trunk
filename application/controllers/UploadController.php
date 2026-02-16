<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class UploadController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function buildThumbImg($file)
    {
        $thumbnail_img = FCPATH . 'preview/' . Token() . ".jpeg";

        $image = new \Imagick($file);
        $image->thumbnailImage($image->getImageWidth(), $image->getImageHeight());
        $image->setCompressionQuality(100);
        $image->setFormat("jpeg");
        $image->writeImage($thumbnail_img);

        $file = file_get_contents($thumbnail_img);

        unlink($thumbnail_img);

        return 'data:image/jpeg;base64,' . base64_encode($file);
    }


    function convertPNGtoJpeg($in, $out)
    {
        $image = imagecreatefrompng($in);

        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);

        $quality = 50; // 0 = worst / smaller file, 100 = better / bigger file 
        imagejpeg($bg, $out, $quality);
        imagedestroy($bg);
    }


    function buildThumbPdf($file)
    {
        $thumbnail_img = FCPATH . 'preview/' . Token() . ".jpeg";

        $image = new \Imagick($file . "[0]");
        $image->thumbnailImage($image->getImageWidth(), $image->getImageHeight());
        $image->setCompressionQuality(100);
        $image->setFormat("jpeg");
        $image->writeImage($thumbnail_img);

        $file = file_get_contents($thumbnail_img);
        return 'data:image/jpeg;base64,' . base64_encode($file);
    }


    function buildThumbVideo($file)
    {

        $thumbnail = FCPATH . 'preview/' . Token() . ".jpeg";
        //$thumbnail = FCPATH . 'temp_file/' . Token() . ".jpeg";

        $cmd = sprintf(
            'ffmpeg -i %s -ss %s -f image2 -vframes 1 %s',
            $file,
            3,
            $thumbnail
        );

        //$cmd = sprintf("ffmpeg -i {$file} -ss 3 -f image2 -vframes 1 {$thumbnail} ");

        exec($cmd);

        $file = file_get_contents($thumbnail);
        return 'data:image/jpeg;base64,' . base64_encode($thumbnail);
    }


    function Upload()
    {
        parent::checkSession();

        $data = $this->input->post();
        $key_remote_id = $data['key_remote_id'];

        $file_ext = strtolower(pathinfo($_FILES["arq"]["name"], PATHINFO_EXTENSION));

        $file = Token() . "." . $file_ext;

        $config = array(
            'upload_path'   => FCPATH . '/files',
            'allowed_types' => 'txt|jpg|jpeg|pdf|wav|mp4|ogg|png|xls|xlsx|ods|csv|doc|docx',
            'file_name'     => $file,
            'overwrite'     => true,
            'max_size'      => '50000'
        );

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('arq')) {
            echo json_encode(
                array(
                    'status' => 401,
                    'erros' => $this->upload->display_errors()
                )
            );
        } else {

            switch ($file_ext) {
                case 'png':
                case 'jpg':
                case 'jpeg':
                    $media_type = 3; // Imagem //
                    break;
                case 'ogg':
                case 'wav':
                    $media_type = 2; // Audio //
                    break;
                case 'mp4':
                    $media_type = 5; // Video //
                    break;
                default:
                    $media_type = 4; // Document //
                    break;
            }

            switch ($file_ext) {
                case 'png':

                    $img_png = Token() . ".jpeg";

                    $this->convertPNGtoJpeg(FCPATH . 'files/' . $file, FCPATH . 'files/' . $img_png);

                    unlink(FCPATH . 'files/' . $file);

                    echo json_encode(
                        array(
                            'status' => 200,
                            'key_remote_id' => $key_remote_id,
                            'media_type' => $media_type,
                            'media_caption' => $_FILES["arq"]["name"],
                            'media_url' => base_url() . 'files/' . $img_png,
                            'thumb_image' => $this->buildThumbImg(FCPATH . 'files/' . $img_png),
                            'media_mime_type' => $_FILES["arq"]["type"],
                        )
                    );
                    break;
                case 'jpeg':
                case 'jpg':
                    echo json_encode(
                        array(
                            'status' => 200,
                            'key_remote_id' => $key_remote_id,
                            'media_type' => $media_type,
                            'media_caption' => $_FILES["arq"]["name"],
                            'media_url' => base_url() . 'files/' . $file,
                            'thumb_image' => $this->buildThumbImg(FCPATH . 'files/' . $file),
                            'media_mime_type' => $_FILES["arq"]["type"],
                        )
                    );
                    break;
                    // case 'mp4':
                    //     $thumb_video = "https://trello-attachments.s3.amazonaws.com/5ef337d098359a3f75e82eec/5f464daa140bec5dc2d2a521/9ceec675c4684fd05afb62e3a2da5818/image.jpg";

                    //     echo json_encode(
                    //         array(
                    //             'status' => 200,
                    //             'media_type' => $media_type,
                    //             'thumb_image' => $this->buildThumbImg($thumb_video),
                    //             'media_url' => base_url() . 'files/' . $file,
                    //             'media_caption' => $_FILES["arq"]["name"],
                    //             'media_mime_type' => $_FILES["arq"]["type"],
                    //         )
                    //     );
                    //     break;
                case 'ogg':
                case 'wav':
                    echo json_encode(
                        array(
                            'status' => 200,
                            'key_remote_id' => $key_remote_id,
                            'media_type' => $media_type,
                            'media_caption' => $_FILES["arq"]["name"],
                            'media_url' => base_url() . 'files/' . $file,
                            'thumb_image' => $this->buildThumbVideo(FCPATH . 'files/' . $file),
                            'media_mime_type' => $_FILES["arq"]["type"],
                        )
                    );
                    break;
                case 'pdf':
                    echo json_encode(
                        array(
                            'status' => 200,
                            'key_remote_id' => $key_remote_id,
                            'media_type' => $media_type,
                            'media_url' => base_url() . 'files/' . $file,
                            'media_caption' => $_FILES["arq"]["name"],
                            'media_mime_type' => $_FILES["arq"]["type"],
                            'thumb_image' => $this->buildThumbPdf(FCPATH . 'files/' . $file),
                        )
                    );
                    break;
                case 'mp4':
                    echo json_encode(
                        array(
                            'status' => 200,
                            'key_remote_id' => $key_remote_id,
                            'media_type' => $media_type,
                            'media_caption' => $_FILES["arq"]["name"],
                            'media_url' => base_url() . 'files/' . $file,
                            // 'thumb_image' => $this->buildThumbVideo(FCPATH . 'files/' . $file),
                            'media_mime_type' => $_FILES["arq"]["type"],
                        )
                    );
                    break;
                default:
                    echo json_encode(
                        array(
                            'status' => 200,
                            'key_remote_id' => $key_remote_id,
                            'media_type' => $media_type,
                            'media_url' => base_url() . 'files/' . $file,
                            'media_title' => $_FILES["arq"]["name"],
                            'media_caption' => $_FILES["arq"]["name"],
                            'media_mime_type' => $_FILES["arq"]["type"],
                            'thumb_image' => null,
                        )
                    );
                    break;
            }
        }
    }


    function UploadPictureProduct()
    {
        parent::checkSession();

        $post = $this->input->post();

        $path = FCPATH . "products/";
        $file = Token() . ".jpeg";

        if (file_exists($path . $file)) {
            unlink($path . $file);
        }

        $config = array(
            'upload_path'   => $path,
            'allowed_types' => 'jpeg|jpg',
            'file_name'     => $file,
            'max_size'      => '5000'
        );

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('arq')) {

            //var_dump($this->upload);

            $thumbnail_img = '/var/www/html/preview/' . Token() . ".jpeg";

            $image = new \Imagick('/var/www/html/products/' . $file);

            $image->thumbnailImage($image->getImageWidth(), $image->getImageHeight());
            $image->setCompressionQuality(100);
            $image->setFormat("jpeg");
            $image->writeImage($thumbnail_img);

            $thumbnail =  'data:image/jpeg;base64,' . base64_encode(file_get_contents($thumbnail_img));

            return $this->output
                ->set_status_header(200)
                ->set_output(
                    json_encode(
                        array(
                            'media_caption' => $this->upload->client_name,
                            'media_mime_type' => $this->upload->file_type,
                            'media_url' => base_url() . 'products/' . $file,
                            'base64' => $thumbnail
                        )
                    )
                );
        } else {
            return $this->output
                ->set_output(json_encode(array('error' => $this->upload->display_errors())))
                ->set_status_header(500);
        }
    }

    
    function UploadProfilePicture()
    {
        parent::checkSession();

        $post = $this->input->post();

        $path = FCPATH . "profiles/";
        $file = $post['key_remote_id'] . ".jpeg";

        if (file_exists($path . $file)) {
            unlink($path . $file);
        }

        $config = array(
            'upload_path'   => $path,
            'allowed_types' => 'jpeg|jpg',
            'file_name'     => $file,
            'max_size'      => '10000'
        );

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('arq')) {

            if ($post['key_remote_id'] == $this->session->userdata('key_remote_id')) {
                $newdata = array(
                    'picture'     =>  $this->User_model->GetProfilePicture($post['key_remote_id'])
                );

                $this->session->set_userdata($newdata);
            }

            return $this->output
                ->set_status_header(200)
                ->set_output(base_url() . 'profiles/' . $file);
        } else {
            return $this->output
                ->set_output(json_encode(array('error' => $this->upload->display_errors())))
                ->set_status_header(500);
        }
    }
}
