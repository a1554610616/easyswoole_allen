<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2019/1/26
 * Time: 11:52 PM
 */

namespace App\HttpController;

use App\Middleware\CorsMiddleware;
use App\Middleware\ValidateCsrfToken;
use EasySwoole\FastCache\Cache;
use EasySwoole\Http\AbstractInterface\Controller;
use Swlib\Saber;
use Swlib\SaberGM;

class Index extends Controller
{
    public function index()
    {
        $request = $this->request();
        $response = $this->response();

        $this->writeJson(200, 'hello world');
    }

    /**
     * 获取csrf_token
     */
    public function getCsrfToken()
    {
        $this->writeJson(200, $this->session()->get('csrf_token'));
    }

    public function testSaber()
    {
        [$html] = SaberGM::list([
            'uri' => [
                'http://gold.kingnet.com'
            ],
        ]);
        var_dump($html->getParsedHtml()->getElementsByTagName('h1')->item(0)->textContent);

        /*$session = Saber::session([
            'base_uri' => 'http://www.blog.com',
            'redirect' => 0
        ]);
        $redirect_url = base64_encode('hehe');
        $res=$session->post('/sso/server/login?redirect_url='.$redirect_url,['user'=>'demo','pwd'=>'demo']);

        echo $res->body;

        $getRes = $session->get('/sso/server/login')->body;
        echo $getRes;*/

    }

    public function onRequest(? string $action): ?bool
    {
        //全局要排除中间件的方法
        $this->middlewareExcept = [
            // Index::class.'\getCsrfToken',
        ];
        //要继承的中间件
        $this->middleware = [
            CorsMiddleware::class,
            ValidateCsrfToken::class,
        ];
        return parent::onRequest($action);
    }
}