import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const addChangeListener = (container, filter) => {
    container.addEventListener('change', (element) => {
        const urlValue = element.target.value
        const isAll = urlValue === 'all'

        let newUrl = new URL(window.location.href)

        isAll
            ? newUrl.searchParams.delete(filter)
            : newUrl.searchParams.set(filter, urlValue)

        window.location.href = newUrl
    })
}

const activeFilters = document.querySelector('#filtersActive')
const emptyFilters = document.querySelector('#filtersEmpty')
const passedFilters = document.querySelector('#filtersPassed')


addChangeListener(activeFilters, 'state')
addChangeListener(emptyFilters, 'empty')
addChangeListener(passedFilters, 'passed')