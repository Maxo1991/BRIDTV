<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Videos Controller
 *
 * @property \App\Model\Table\VideosTable $Videos
 *
 * @method \App\Model\Entity\Video[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VideosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $videos = $this->paginate($this->Videos);

        $this->set(compact('videos'));
    }

    /**
     * View method
     *
     * @param string|null $id Video id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $video = $this->Videos->get($id, [
            'contain' => [],
        ]);

        $this->set('video', $video);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $video = $this->Videos->newEntity();
        if ($this->request->is('post')) {
            $video['download_link'] = $this->request->data['yt_video_link'];
            if(!empty($video['download_link'])){
                $video = $this->Videos->patchEntity($video, $this->request->getData());
                $video['title'] = $this->request->data['title'];
                $video['download_link'] = $this->request->data['yt_video_link'];
                $a = $this->request->data['yt_video_link'];
                if (strpos($a, 'https://www.youtube.com') !== false || strpos($a, 'https://youtu.be') !== false) {
                    $video['website'] = 'youtube';
                }
                if ($this->Videos->save($video)) {
                    $this->Flash->success(__('The video has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The video could not be saved. Please, try again.'));
            }else{
                $this->Flash->error(__('You must enter Source field!'));
            }
        }
        $this->set(compact('video'));
    }

    public function ajax(){
        if(isset($_POST['source'])) {
            $yt_video_link = $_POST['source'];
            if (strpos($yt_video_link, 'https://www.youtube.com') !== false || strpos($yt_video_link, 'https://youtu.be') !== false) {
                $website = "youtube";
            }
            $yt_title = $_POST['title'];
            if(!empty($yt_video_link)) {
                  parse_str( parse_url( $yt_video_link, PHP_URL_QUERY ), $vars );
                  $vid = isset($vars['v']) ? $vars['v'] : '';
                if($vid) {
                    parse_str(file_get_contents("https://youtube.com/get_video_info?video_id=".$vid),$info);
                    $playabilityJson = json_decode($info['player_response']);
                    $duration = $playabilityJson->microformat->playerMicroformatRenderer->lengthSeconds;
                    $formats = $playabilityJson->streamingData->formats;
                    $adaptiveFormats = $playabilityJson->streamingData->adaptiveFormats;
                    //Checking playable or not
                    $IsPlayable = $playabilityJson->playabilityStatus->status;
                    $result = array();
                    if(!empty($info) && $info['status'] == 'ok' && strtolower($IsPlayable) == 'ok') {
                        $i=0;
                        $videoInfo = json_decode($info['player_response']);
                        $thumbnail = $videoInfo->videoDetails->thumbnail->thumbnails[3]->url;
                        $j=0;
                        foreach($formats as $stream) {
                            $streamUrl = $stream->url;
                            $type = explode(";", $stream->mimeType);
                            $qualityLabel='';
                            $bitrate = $stream->bitrate;
                            if(!empty($stream->qualityLabel)) {
                                $qualityLabel = $stream->qualityLabel;
                            }
                            if(!empty($stream->contentLength)){
                                $videoOptionsOrg[$j]['size'] = $stream->contentLength;
                            } else {
                                $videoOptionsOrg[$j]['size'] = 0;
                            }
                            $videoOptionsOrg[$j]['link'] = $streamUrl;
                            $videoOptionsOrg[$j]['type'] = $type[0];
                            $videoOptionsOrg[$j]['quality'] = $qualityLabel;
                            $videoOptionsOrg[$j]['bitrate'] = $bitrate;
                            $j++;
                        };
                        $result['videos'] = array(
                            'thumbnail'=>$thumbnail,
                            'title'=>$yt_title,
                            'website'=>$website,
                            'duration' => $duration,
                            'formats'=>$videoOptionsOrg
                        );
                        $this->response->body(json_encode($result));

                        return $this->response;
                    }
                }
            }
            else {
                $error = "Enter YouTube video URL";
            }
        }
    }

    public function download(){
        $actual_link = $_SERVER['REQUEST_URI'];
        $positionStart = strpos($actual_link, "https://");
        $positionEnd = strpos($actual_link, "&title=");
        $url = substr($actual_link, $positionStart, $positionEnd - $positionStart);
        $playLink = urldecode($url);

        $downloadURL = urldecode($_GET['link']);

        $type = urldecode($_GET['type']);
        $title = urldecode($_GET['title']);
        $bitrate = urldecode($_GET['bitrate']);
        $duration = $_GET['duration'];
        $website = urldecode($_GET['website']);
        $size = urldecode($_GET['size']);

        $typeArr = explode("/",$type);
        $extension = $typeArr[1];

        $fileName = $title.'.'.$extension;

        if($size < 31457010){
            $video = $this->Videos->newEntity();
            $video['title'] = $title;
            $video['bitrate'] = $bitrate;
            $video['duration'] = $duration;
            $video['website'] = $website;
            $video['download_link'] = $downloadURL;
            $video['url'] = $playLink;
            $video['size'] = $size;
            $this->Videos->save($video);

            $this->Flash->success(__('The video has been saved.'));

            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('File must be smaller than 30MB'));
            return $this->redirect(['action' => 'index']);
        }

////        download to computer
//            if (!empty($downloadURL)) {
//                header ('Content-type: octet/stream');
//                header("Cache-Control: public");
//                header("Content-Description: File Transfer");
//                header("Content-Disposition: attachment;filename=\"$fileName\"");
//                header("Content-Transfer-Encoding: binary");
//                readfile($downloadURL);
//            }
    }

    /**
     * Edit method
     *
     * @param string|null $id Video id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $video = $this->Videos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $video = $this->Videos->patchEntity($video, $this->request->getData());
            if ($this->Videos->save($video)) {
                $this->Flash->success(__('The video has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The video could not be saved. Please, try again.'));
        }
        $this->set(compact('video'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Video id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $video = $this->Videos->get($id);
        if ($this->Videos->delete($video)) {
            $this->Flash->success(__('The video has been deleted.'));
        } else {
            $this->Flash->error(__('The video could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
