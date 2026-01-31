import './bootstrap';

window.getCookie = (name) => {
    let value = ';' + document.cookie;
    let parts = value.split(';' + name + '=');
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
};
