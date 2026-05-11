document.addEventListener('DOMContentLoaded', function () {
    const component = document.getElementById('sidebar-tabs')
    const tabs = component!.querySelectorAll('.tab');
    console.log('here')
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('tab-active'));
            tab.classList.add('tab-active');
        });
    });
});