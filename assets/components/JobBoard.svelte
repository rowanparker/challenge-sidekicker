<script>
import {onMount} from "svelte";
import List from './JobBoard/List.svelte';
import Show from './JobBoard/Show.svelte';
import LoadingBlock from './LoadingBlock.svelte';

let loading = true;
let iri = null;
let jobs = [];

onMount(() => {
    fetch(`/api/jobs`).then(r => r.json()).then(j => {
        jobs = j['hydra:member'];
        loading = false;
    });
})

const showJob = (e) => {
    iri = e.detail.iri;
}
const showList = () => {
    iri = null;
}
</script>

{#if loading}
    <LoadingBlock/>
{:else if iri !== null}
    <Show iri={iri} on:showList={showList}/>
{:else}
    <List jobs={jobs} on:showJob={showJob}/>
{/if}