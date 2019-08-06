<?php

    namespace Lab19\Cart\Module\Products;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasOne;

    class Product extends Model {

        /**
         * Scopes
         */
        const IN_STOCK = 'IN_STOCK';
        const OUT_OF_STOCK = 'OUT_OF_STOCK';


        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 'cart_products';

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'title', 'published'
        ];

        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
        ];

        /**
         * The attributes that should be cast to native types.
         *
         * @var array
         */
        protected $casts = [
        ];

        /**
         * In stock products scope
         *
         * @var $query
         */
        public function scopeInStock( $query ){
            return $query->where('status', static::IN_STOCK );
        }

        /**
         * Out of stock products scope
         *
         * @var $query
         */
        public function scopeOutOfStock( $query ){
            return $query->where('status', static::OUT_OF_STOCK );
        }

        /**
         * Published products
         *
         * @var $query
         */
        public function scopePublished( $query ){
            return $query->where('published', 1 );
        }

        /**
         * Unpublished products
         *
         * @var $query
         */
        public function scopeUnpublished( $query ){
            return $query->where('published', 0 );
        }

    }
