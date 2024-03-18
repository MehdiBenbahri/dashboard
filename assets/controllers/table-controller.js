// A Stimulus JavaScript controller file
// https://stimulus.hotwired.dev
// @see templates/security/login.html.twig
// More info on Symfony UX https://ux.symfony.com

import { Controller } from '@hotwired/stimulus';

/*
 * The following line makes this controller "lazy": it won't be downloaded until needed
 * See https://github.com/symfony/stimulus-bridge#lazy-controllers
 */
/* stimulusFetch: 'lazy' */
export default class table extends Controller {

    static targets = [
        'table', 
        'spinner'
    ];

    

    setLoading() {
        this.tableTarget.classList.add('loading');
        this.spinnerTarget.classList.remove('d-none');
    }

    resolveColumnSorting(event) {
        const url = location.search.substring(1);
        const urlObj = JSON.parse('{"' + decodeURI(url).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
        console.log(urlObj);
        console.log(event.params);
        console.log(urlObj[`${colId}-sort-action`]);

    }

}
