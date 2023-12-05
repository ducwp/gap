<?php
namespace GAPTheme\Kyguionline;

class Form {
  private static $_instance = null;
  public static function instance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  private function __construct() {
    add_filter('wpcf7_validate_number', [$this, 'form_validation'], 20, 2);
    add_filter("wpcf7_form_tag", [$this, 'modify_brands'], 10, 2);
  }

  function form_validation($result, $tag) {
    /* if ('your-email-confirm' == $tag->name) {
      $your_email = isset($_POST['your-email']) ? trim($_POST['your-email']) : '';
      $your_email_confirm = isset($_POST['your-email-confirm']) ? trim($_POST['your-email-confirm']) : '';

      if ($your_email != $your_email_confirm) {
        $result->invalidate($tag, "Are you sure this is the correct address?");
      }
    } */

    $clothes_new_local = absint($_POST['clothes_new_local']);
    $clothes_new_global = absint($_POST['clothes_new_global']);
    $clothes_used_local = absint($_POST['clothes_used_local']);
    $clothes_used_global = absint($_POST['clothes_used_global']);

    if ($clothes_new_local + $clothes_new_global + $clothes_used_local + $clothes_used_global < 5) {
      $tag = new \WPCF7_Shortcode($tag);
      if (in_array($tag->name, ['clothes_new_local', 'clothes_new_global', 'clothes_used_local', 'clothes_used_global'])) { // validate name field only
        $result->invalidate($tag, "Tổng sản phẩm quần áo phải từ 5 trở lên.");
      }
    }

    return $result;
  }

  function modify_brands($scanned_tag, $replace) {

    //Clothes brands - Local
    if (in_array($scanned_tag["name"], ['clothes_new_local_brand', 'clothes_used_local_brand'])) {
      $clothes_brands = ['El.rever/ Elpis','Joie des Roses','Dzung Biez','Gout de Jun','Kido','Libe','Rubies','Dirty coin','Bad Rabbits','Marc','Hnoss','Dear Jose','Ceci Cela','Elise','Nem','Marc','Len Clothing','Anis','Aoem','Popbirdy','Dotie','Gago','Mifworkshop','Strikeapose','Sibling house','Rocky denim','Tinfour','Nudieye','Fleur','Aguja','DVRK','Cocosin','Lider','Colin','Tuby Catu','The Swan','Ruchan','DII','Coqui','Itscicico','Tatuchu','Jubin','Levents','Oia','Ononmade','Naked','She by Shj','Kiserine'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($clothes_brands as $clothes_brand) {
        $scanned_tag['values'][] = $clothes_brand;
        $scanned_tag['labels'][] = $clothes_brand;
      }
    }

    //Clothes brands - Global
    if (in_array($scanned_tag["name"], ['clothes_new_global_brand', 'clothes_used_global_brand'])) {
      $clothes_brands = ['Zara','H&M','Stradivarius','Pull & Bear','Mango','Cotton:On','Uniqlo','On:Off','Burberry','Versace','Kenzo','Tommy','Tory Burch','Michael Kors'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($clothes_brands as $clothes_brand) {
        $scanned_tag['values'][] = $clothes_brand;
        $scanned_tag['labels'][] = $clothes_brand;
      }
    }

    //Bag brands
    if (in_array($scanned_tag["name"], ['bag_new_brand', 'bag_used_brand'])) {
      $bag_brands = ['Chanel','Christian Dior','Gucci','Yves Saint Lauren','Versace','Charles & Keith','Aldo','Lyn','Pedro','Bunny Jelly','Marhen.J','MLB','MCM','Juno','Vascara','TTWN Bear','Coach','Kate Spade','Tory Burch','Michael Kors','Celine','Louis Vuitton','Marc Jacobs','Prada','Salvatore','Lesac','Chaufifth','Floral Punk'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($bag_brands as $bag_brand) {
        $scanned_tag['values'][] = $bag_brand;
        $scanned_tag['labels'][] = $bag_brand;
      }
    }

    //Shoe brands
    if (in_array($scanned_tag["name"], ['shoe_new_brand', 'shoe_used_brand'])) {
      $shoe_brands = ['Chanel','Christian Dior','Gucci','Yves Saint Lauren','Versace','Charles & Keith','Aldo','Lyn','Pedro','Bunny Jelly','Marhen.J','MLB','MCM','Juno','Vascara','TTWN Bear','Coach','Kate Spade','Tory Burch','Michael Kors','Bottega','Converse','Marc Jacobs','Palladium','Salvatore'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($shoe_brands as $shoe_brand) {
        $scanned_tag['values'][] = $shoe_brand;
        $scanned_tag['labels'][] = $shoe_brand;
      }
    }

    //Cosmetic brands
    if (in_array($scanned_tag["name"], ['cosmetic_new_brand', 'cosmetic_used_brand'])) {
      $cosmetic_brands = ['Chanel','Dior','Gucci','Yves Saint Lauren','Bath & Body works','Victoria\'s Secrect','Bioderma','Biotherm','Bobbi Brown','Cerave','Estee Lauder','Nars','Charlotte Tilbury','Christian Louboutin','Clarins','Cle de Peau','Clean & Clear','Clinique','Colour Pop','Curel','d program','Decorte','DHC','Laroche Posay','Eucerin','Fenty Beauty by Rihanna','Guerlain','Innisfree','The Face Shop','La Mer','Lancome','Laneige','Lanvin','Origins','Laura Mercier','MAC','Maybeline','Merzy','Black Rouge','Moroccanoil','Morphe','Murad','Paula\'s Choice','Neutrogena','Palmer','Revlon','Shiseido','Shu Uemura','Simple','SK-II','Skin Ceuticals','Smashbox','Sulwhasoo','Ted Baker','The Body Shop','Colourpop','Obagi','The Ordinary','The Balm','Too Faced','Tsubaki','Vichy','Age 20\'s'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($cosmetic_brands as $cosmetic_brand) {
        $scanned_tag['values'][] = $cosmetic_brand;
        $scanned_tag['labels'][] = $cosmetic_brand;
      }
    }

    //Shoe brands
    if (in_array($scanned_tag["name"], ['perfume_new_brand', 'perfume_used_brand'])) {
      $perfume_brands = ['Chanel','Dior','Gucci','Yves Saint Lauren','Versace','Adidas','DKNY','Balenciaga','Bath & Body works','Victoria\'s Secrect','Bottega','Britney Spears','Bugatti','Bulgari','Burberry','Calvin Klein','Carolina Herrera','Cartier','Celine','Chloe','Chopard','Coach','Coast','Creed','Davidoff','Dolce & Gabbana','Elizabeth Arden','Giorgio Armani','Givenchy','Guess','Hera','Hermes','Hugo Boss','Jean Couturier','Jean Paul Gaultier','Jimmy Choo','Jo Malone','Juicy Couture','Katy Perry','Kenzo','Killian','Lady Gaga','Lancome','Le Labo','Louis Vuitton','Marc Jacobs','MCM','Miu Miu','Bond 9','MontBlanC','Moschino','Narciso','Naomi Campbell','Nina Ricci','Paco Rabanne','Prada','Ralph Lauren','Rihanna','Roberto Cavalli','Sephora','Ted Baker','Tom Ford','Valentino','Vera Wang','Vikto & Rolf','Wet n Wild','Yves Rocher','Anne Klein'];
      $scanned_tag['values'] = []; //replace default value
      $scanned_tag['labels'] = []; //replace default value
      foreach ($perfume_brands as $perfume_brand) {
        $scanned_tag['values'][] = $perfume_brand;
        $scanned_tag['labels'][] = $perfume_brand;
      }
    }

    return $scanned_tag;

  }
}