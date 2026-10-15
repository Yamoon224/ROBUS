const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');

const template = fs.readFileSync(path.join(__dirname, '../resources/views/components/take-action-button.blade.php'), 'utf8');
const handler = template.match(/x-on:click="([\s\S]*?)"/)[1]
    .replace('{{ $action }}', 'ChooseUpdateTransaction')
    .replace('{{ $itemId }}', '1')
    .replaceAll('&amp;', '&');
const AsyncFunction = Object.getPrototypeOf(async function () {}).constructor;
const click = new AsyncFunction('state', 'setTimeout', `with (state) { ${handler} }`);
const stateFor = (call) => ({
    processing: false, success: false, error: false, errorMessage: '',
    $wire: { ChooseUpdateTransaction: call },
});

test('successful claim stops loading and displays success', async () => {
    const state = stateFor(async () => ({success: true}));
    const timers = [];
    await click(state, (callback) => timers.push(callback));
    assert.equal(state.processing, false);
    assert.equal(state.success, true);
    assert.equal(state.error, false);
    timers[0]();
    assert.equal(state.success, false);
});

test('business failure or denied permission stops loading and allows retry', async () => {
    const state = stateFor(async () => ({success: false, message: 'Non autorisé'}));
    await click(state, () => {});
    assert.equal(state.processing, false);
    assert.equal(state.success, false);
    assert.equal(state.error, true);
    assert.equal(state.errorMessage, 'Non autorisé');
    state.$wire.ChooseUpdateTransaction = async () => ({success: true});
    await click(state, () => {});
    assert.equal(state.success, true);
    assert.equal(state.error, false);
});

test('network failure stops loading without claiming success', async () => {
    const state = stateFor(async () => { throw new Error('Network failure'); });
    await click(state, () => {});
    assert.equal(state.processing, false);
    assert.equal(state.success, false);
    assert.equal(state.error, true);
    assert.match(state.errorMessage, /Vérifiez la transaction/);
});

test('missing server result does not show false success', async () => {
    const state = stateFor(async () => undefined);
    await click(state, () => {});
    assert.equal(state.processing, false);
    assert.equal(state.success, false);
    assert.equal(state.error, true);
});

test('double click calls server once while the request is pending', async () => {
    let calls = 0;
    let resolve;
    const state = stateFor(() => { calls++; return new Promise(done => { resolve = done; }); });
    const first = click(state, () => {});
    await click(state, () => {});
    assert.equal(calls, 1);
    resolve({success: true});
    await first;
    await click(state, () => {});
    assert.equal(calls, 1);
});
