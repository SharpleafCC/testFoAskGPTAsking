export const dataLayer = {

    windowPath: window.location.pathname,

	init() {
        this.initializeDataLayer();

        // Handle age gate passed through custom events to make it easier to track page views via GTM
        this.ageGatePassed();

        // Shopify helpers. Uncomment if the website is using shopify
        // this.handleAddToCartDataLayer();
        // this.handleBeginCheckoutDataLayer();
        // this.handleCollctionsPageDataLayer();
        // this.handleViewItemDataLayer();
    },

    initializeDataLayer: () => {
        window.dataLayer = window.dataLayer || [];
    },

    pushToDataLayer: ( object ) => {
        window.dataLayer.push( object );
    },

    clearEvent: (eventName) => {
        const clearEvent = {};
        clearEvent[eventName] = null;

        this.pushToDataLayer( clearEvent );
    },

    /**
     * Fires a custom event to GTM called Age Gate Passed and includes a variable that contains the path of the current site (aka https://example.com/about-us passes back "about-us" to GTM in the variable ageGatePassedPage)
     * Allows us to create one tag that tracks page view of a page based on 2 triggers - the custom Age Gate Passed event has fired OR there is a page view where the age-gate-passed cookie is set to true
     */
    ageGatePassed: () => {
        const ageGateYes = document.querySelector('#age-gate__button--yes');

        if ( ageGateYes ) {
            ageGateYes.addEventListener('click', () => {
                this.pushToDataLayer({
                    event: 'Age Gate Passed',
                    ageGatePassedPage: window.location.pathname
                });
            });
        }
    },

    /**
     * Shopify helper - sends a `view_item_list` event to GTM to be tracked. Includes any items on a collections page
     */
    handleCollctionsPageDataLayer: () => {

        if ( this.windowPath.includes('collections') ) {
            const productItems = document.querySelectorAll('section[data-section-type="collection"] .ProductList .ProductItem');
    
            let viewItemListEvent = {
                event: 'view_item_list',
                ecommerce: {
                    items: []
                }
            };
    
            if ( productItems.length > 0 ) {
                productItems.forEach( (item, key) => {
    
                    const productListItem = item.querySelector('.ProductItem__ImageWrapper'); // the clickable Image element
                    const productTitleAnchor = item.querySelector('.ProductItem__Title a'); // the clickable title element
    
                    const index = key; // Placement of the item in the list
                    const item_name = productTitleAnchor.textContent; // The name of the item
                    const price = parseFloat( item.querySelector('.ProductItem__Price').textContent.replace('$', '') ); // The price
                    const item_list_name = 'ProductList'; // The collection name
                    const item_list_id = 'ProductList';
                    
                    const collectionTemplate = document.querySelector('section[data-section-id="collection-template"]');
                    let item_category = '';
    
                    // Get the collection type
                    if ( collectionTemplate ) {
                        const collectionData = JSON.parse( collectionTemplate.dataset.sectionSettings );
                        
                        if ( collectionData.collectionUrl && collectionData.collectionUrl.includes('/collections/') ) {
                            item_category = collectionData.collectionUrl.replace('/collections/', '');
                        }
                    }
    
                    viewItemListEvent.ecommerce.items.push({
                        item_name,
                        price,
                        item_list_name,
                        index,
                        item_category,
                        item_list_name,
                        item_list_id
                    });
    
                    // The object responsible for the `select_item` event. Passes necessary data to GTM
                    const selectItemEvent = {
                        event: 'select_item',
                        ecommerce: {
                            items: [
                                {
                                    item_name,
                                    price,
                                    item_list_name,
                                    index,
                                    item_category,
                                    item_list_name,
                                    item_list_id
                                }
                            ]
                        }
                    };
    
                    productListItem.addEventListener('click', (e) => {
                        // Clear previous object
                        this.clearEvent( 'ecommerce' );
    
                        this.pushToDataLayer( selectItemEvent );
                    });
    
                    productTitleAnchor.addEventListener('click', (e) => {
                        // Clear previous object
                        this.clearEvent( 'ecommerce' );
    
                        this.pushToDataLayer( selectItemEvent );
                    });
                });
            }
    
            // Sent the view list event if we're looking at a collection
            if ( this.windowPath.substring(1).split('/').length == 2 ) {
    
                this.clearEvent( 'ecommerce' );
                this.pushToDataLayer( viewItemListEvent );
            }
        }
    },
    
    /**
     * Shopify helper - sends a `view_item` event to the GTM datalayer to track when a user has clicked an item and has been send to its PDP
     */
    handleViewItemDataLayer: () => {
        const bodyClassList = document.body.classList;
    
        if ( bodyClassList.contains('template-product') ) {
            const item_name = document.querySelector('.ProductMeta__Title').textContent;
            const price = parseFloat( document.querySelector('.ProductMeta__Price').textContent.replace('$', '') );
            let item_category = '';
    
            if ( this.windowPath ) {
                const windowPathArray = this.windowPath.substring(1).split('/');
    
                item_category = windowPathArray[1];
            }
    
            const viewItemEvent = {
                event: 'view_item',
                ecommerce: {
                    items: [
                        {
                            item_name,
                            price,
                            item_category
                        }
                    ]
                }
            };
    
            this.clearEvent( 'ecommerce' );
    
            this.pushToDataLayer( viewItemEvent );
        }
    },
    
    /**
     * Shopify helper - sends a `add_to_cart` event to the GTM datalayer whenever a user clicks add to cart from a PDP
     */
    handleAddToCartDataLayer: () => {
        document.addEventListener('product:added', (e) => {
    
            const item_name = e.detail.variant.name;
            const price = e.detail.variant.price / 100;
            const item_id = e.detail.variant.id;
            const quantity = e.detail.quantity;
    
            let item_category = '';
    
            if ( this.windowPath ) {
                const windowPathArray = this.windowPath.substring(1).split('/');
                item_category = windowPathArray[1];
            }
    
            const addToCartEvent = {
                event: 'add_to_cart',
                ecommerce: {
                    items: [
                        {
                            item_name,
                            price,
                            item_category,
                            quantity,
                            item_id
                        }
                    ]
                }
            };
    
            this.clearEvent( 'ecommerce' );
    
            this.pushToDataLayer( addToCartEvent );
    
        });
    },
    
    /**
     * Shopify helper - sends a `begin_checkout` to the GTM datalayer whenever a user clicks checkout from the cart fly out
     */
    handleBeginCheckoutDataLayer: () => {
        document.addEventListener('click', (e) => {
    
            if ( e.target.classList.contains('Cart__Checkout') || ( e.target.parentElement && e.target.parentElement.classList.contains('Cart__Checkout') ) ) {
                const cartItemList = document.querySelectorAll('.Cart__ItemList .CartItemWrapper');
    
                const beginCheckoutEvent = {
                    event: 'begin_checkout',
                    ecommerce: {
                        items: []
                    }
                };
    
                if ( cartItemList.length > 0 ) {
                    cartItemList.forEach(item => {
                        const item_name = item.querySelector('.CartItem__Title a').textContent;
                        const quantity = parseInt( item.querySelector('.QuantitySelector__CurrentQuantity').value );
                        const price = parseFloat( item.querySelector('.CartItem__Price').textContent.replace('$', '') );
                        
                        beginCheckoutEvent.ecommerce.items.push({
                            item_name,
                            price,
                            quantity
                        });
                    });
    
                    this.clearEvent( 'ecommerce' );
                    this.pushToDataLayer( beginCheckoutEvent );
                }
            }
        });
    }
};