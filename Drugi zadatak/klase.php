/////////////////////////////////////////////////////////////////////////
///////////KLASE ZA SEO OPTIMIZACIJU/////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
<?php
class generator_pro
{
	private $urlForbidenCharacters = array('%',': ','?','š','Š','ž','Ž','ć','Ć','č','Č','đ','Đ','. ','.',', ',' - ','/',' ',"'",'`','!','+','(',')','"','®','!','?','\\','*','\'','^','&','#','<','>','; ',';','{','}','(',')','|','~','[',']');
	private $urlValidCharacters = array('','-','','s','S','z','Z','c','C','c','C','dj','Dj','','','-','-','-','-','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	public function create_seo_title($element)
	{
		$element = ltrim($element);
		$element = rtrim($element);
		return str_replace($this->urlForbidenCharacters,$this->urlValidCharacters,$element);
	}
}
class seo
{
	//=========================================== BASIC TAGS =======================================
	private $page_title = "";
	private $page_keywords = "";
	private $page_description = "";
	//=========================================== OPEH GRAPH PROTOCOL ==============================
	private $show_og_code = false;
	private $og_title = "";
	private $og_url = "";
	private $og_desctiption = "";
	private $og_image = "";
	private $og_type = "";
	//=========================================== SEO TITLE VALIDATION ============================
	private $urlForbidenCharacters = array('%',': ','?','š','Š','ž','Ž','ć','Ć','č','Č','đ','Đ','. ','.',', ',' - ','/',' ',"'",'`','!','+','(',')','"','®','!','?','\\','*','\'','^','&','#','<','>','; ',';','{','}','(',')','|','~','[',']');
	private $urlValidCharacters = array('','-','','s','S','z','Z','c','C','c','C','dj','Dj','','','-','-','-','-','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	
	public function set_basic_tags($page_title = "",$page_keywords = "",$page_description = "")
	{
		 $this->page_title = $page_title;
		 $this->page_keywords = $page_keywords;
		 $this->page_description = $page_description;
	}

	public function set_open_graph_protocol_parameters($og_title,$og_url,$og_desctiption,$og_image,$og_type){
		$this->show_og_code = true;
		$this->og_title 		= $og_title;
		$this->og_url 			= $og_url;
		$this->og_desctiption 	= $og_desctiption;
		$this->og_image 		= $og_image;
		$this->og_type 			= $og_type;
	}
	//===================================== ECHO PAGE AND SEO TAGS ===============================
	public function echo_seo_tags()
	{
		?>
<title><?php echo $this->page_title; ?></title>
<meta name="keywords" content="<?php echo $this->page_keywords; ?>" />
<meta name="description" content="<?php echo $this->page_description; ?>" />
<?php if($this->show_og_code){ ?>
<meta property="og:title" content="<?php echo strip_tags($this->strip_string_word($this->og_title,150)); ?>" />
<meta property="og:type" content="<?php echo strip_tags($this->strip_string_word($this->og_type,150)); ?>" />
<meta property="og:url" content="<?php echo strip_tags($this->og_url); ?>" />
<meta property="og:image" content="<?php echo strip_tags($this->og_image); ?>" />
<meta property="og:description" content="<?php echo strip_tags($this->strip_string_word($this->og_desctiption,250)); ?>" /><?php } 
}
	//=============== ADDITIONAL FUNCTIONS ===============================================
	
	//========= CREATE SEO TITLE
	public function create_seo_title($element)
	{
		$element = ltrim($element);
		$element = rtrim($element);
		return str_replace($this->urlForbidenCharacters,$this->urlValidCharacters,$element);
	}
	//========= GOOGLE ANALYTICS
	public function echo_google_analytics_code($ga_code)
	{
		if($ga_code != "")
		{
		?>
        <script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '<?php echo $ga_code; ?>']);
			_gaq.push(['_trackPageview']);
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
		<?php	
		}
	}
	
	//================== PRIVATE FUNCTIONS ==================================================
	private function strip_string_word($string,$length=40)
	{
		if(strlen($string) > $length)
		{
			$string = wordwrap($string, $length);
			$j = strpos($string, "\n");
			if($j) 
				$string = substr($string, 0, $j);
		}else{
			$string = stripslashes($string);
		}
		$string = strip_tags($string);
		return $string;
	}
}
?>

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////USER CONTROLLER ZA LOGIN I REGISTER(CIST PHP)/////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
<?php
class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function register() {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'confirmPassword' => '',
            'usernameError' => '',
            'emailError' => '',
            'passwordError' => '',
            'confirmPasswordError' => ''
        ];

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

              $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirmPassword' => trim($_POST['confirmPassword']),
                'usernameError' => '',
                'emailError' => '',
                'passwordError' => '',
                'confirmPasswordError' => ''
            ];

            $nameValidation = "/^[a-zA-Z0-9]*$/";
            $passwordValidation = "/^(.{0,7}|[^a-z]*|[^\d]*)$/i";

            //Validate username on letters/numbers
            if (empty($data['username'])) {
                $data['usernameError'] = 'Please enter username.';
            } elseif (!preg_match($nameValidation, $data['username'])) {
                $data['usernameError'] = 'Name can only contain letters and numbers.';
            }

            //Validate email
            if (empty($data['email'])) {
                $data['emailError'] = 'Please enter email address.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['emailError'] = 'Please enter the correct format.';
            } else {
                //Check if email exists.
                if ($this->userModel->findUserByEmail($data['email'])) {
                $data['emailError'] = 'Email is already taken.';
                }
            }

           // Validate password on length, numeric values,
            if(empty($data['password'])){
              $data['passwordError'] = 'Please enter password.';
            } elseif(strlen($data['password']) < 8){
              $data['passwordError'] = 'Password must be at least 8 characters';
            } elseif (preg_match($passwordValidation, $data['password'])) {
              $data['passwordError'] = 'Password must be have at least one numeric value.';
            }

            //Validate confirm password
             if (empty($data['confirmPassword'])) {
                $data['confirmPasswordError'] = 'Please enter password.';
            } else {
                if ($data['password'] != $data['confirmPassword']) {
                $data['confirmPasswordError'] = 'Passwords do not match, please try again.';
                }
            }

            // Make sure that errors are empty
            if (empty($data['usernameError']) && empty($data['emailError']) && empty($data['passwordError']) && empty($data['confirmPasswordError'])) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                //Register user from model function
                if ($this->userModel->register($data)) {
                    //Redirect to the login page
                    header('location: ' . URLROOT . '/users/login');
                } else {
                    die('Something went wrong.');
                }
            }
        }
        $this->view('users/register', $data);
    }

    public function login() {
        $data = [
            'title' => 'Login page',
            'username' => '',
            'password' => '',
            'usernameError' => '',
            'passwordError' => ''
        ];

        //Check for post
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Sanitize post data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'usernameError' => '',
                'passwordError' => '',
            ];
            //Validate username
            if (empty($data['username'])) {
                $data['usernameError'] = 'Please enter a username.';
            }
            //Validate password
            if (empty($data['password'])) {
                $data['passwordError'] = 'Please enter a password.';
            }
            //Check if all errors are empty
            if (empty($data['usernameError']) && empty($data['passwordError'])) {
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['passwordError'] = 'Password or username is incorrect. Please try again.';
                    $this->view('users/login', $data);
                }
            }
        } else {
            $data = [
                'username' => '',
                'password' => '',
                'usernameError' => '',
                'passwordError' => ''
            ];
        }
        $this->view('users/login', $data);
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['username'] = $user->user_name;
        $_SESSION['email'] = $user->user_email;
        header('location:' . URLROOT . '/pages/index');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        header('location:' . URLROOT . '/users/login');
    }
}
?>



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////KLASA ZA PROIZVODE NA ECOMMERCE(KORISTIO SAM LARAVEL)///////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    function index()
    {
        $data = Product::all();
        return view('product', ['products' => $data]);
    }
    function detail($id)
    {
        $data = Product::find($id);
        return view('detail', ['product' => $data]);
    }
    function search(Request $req)
    {
        $data = Product::where('name', 'like', '%' . $req->input('query') . '%')->get();
        return view('search', ['products' => $data]);
    }
    function addToCart(Request $req)
    {
        if($req->session()->has('user'))
        {
            $cart = new Cart();
            $cart->user_id = $req->session()->get('user')['id'];
            $cart->product_id = $req->product_id;
            $cart->save();
            return redirect('/');
        } else {
            return redirect('/login');
        }
    }
    static function cartItem()
    {
        $userId = Session::get('user')['id'];
        return Cart::where('user_id', $userId)->count();
    }
    function cartList()
    {
        $userId = Session::get('user')['id'];
        $products = DB::table('cart')
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->where('cart.user_id', $userId)
            ->select('products.*', 'cart.id as cart_id')
            ->get();
        return view('cartlist', ['products' => $products]);
    }
    function removeCart($id)
    {
        Cart::destroy($id);
        return redirect('cartlist');
    }
    function orderNow()
    {
        $userId = Session::get('user')['id'];
        $total = DB::table('cart')
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->where('cart.user_id', $userId)
            ->sum('products.price');
        return view('ordernow', ['total' => $total]);
    }
    function orderPlace(Request $req)
    {
        $userId = Session::get('user')['id'];
        $allCart = Cart::where('user_id', $userId)->get();
        foreach($allCart as $cart)
        {
            $order = new Order();
            $order->product_id = $cart['product_id'];
            $order->user_id =$cart['user_id'];
            $order->status = "pending";
            $order->address = $req->address;
            $order->save();
            Cart::where('user_id', $userId)->delete();
        }
        return redirect('/');
    }
    function myOrders()
    {
        $userId = Session::get('user')['id'];
        $orders = DB::table('orders')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('orders.user_id', $userId)
            ->get();
        return view('myorders', ['orders' => $orders]);
    }
}

