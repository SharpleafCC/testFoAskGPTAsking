import Splide from "@splidejs/splide";
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';
let splideInstances = [];
const slider = {
    getSplideInstances(){
        return splideInstances;
    },
    init() {
        
        const sliders = document.querySelectorAll('.splide');
        if (sliders.length) {
            for (let i = 0; i < sliders.length; i++) {
                let slider_id = sliders[i].getAttribute('data-slider') || 'splide-' + i;
                sliders[i].classList.add(slider_id);

				//set some default options that differ from the default splide options
                let sliderOptions = {
                    autoHeight: true,
                    rewind: true,
                    omitEnd: true
                };

				//if the slider has a data-autoscroll attribute, set the autoscroll options
                let auto_scroll = sliders[i].getAttribute('data-autoscroll') === 'true';
				//if the slider has a data-autoscroll-speed attribute, set the autoscroll speed
                let auto_scroll_speed = parseInt(sliders[i].getAttribute('data-autoscroll-speed') || '1');
                
                if (auto_scroll) {
                    sliderOptions.autoScroll = {
                        speed: auto_scroll_speed
                    };
                }

                splideInstances[i] = new Splide("." + slider_id, sliderOptions);

				//if the slider has a data-autoscroll attribute, mount the autoscroll extension
                if (auto_scroll) {
                    splideInstances[i].mount({ AutoScroll });
                } else {
                    splideInstances[i].mount();
                }
            }
        }
    }
}

addEventListener('load', (event) => {
    slider.init();
});

export {
    slider
};
