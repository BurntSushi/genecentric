import bpm

import argparse
import multiprocessing as mp

try:
    __cpus = mp.cpu_count()

    # be nice to Funcassociate by default
    __default_cpus = min(6, mp.cpu_count())
except NotImplementedError:
    __cpus = 1
    __default_cpus = 1

parser = argparse.ArgumentParser(
    description='GO enrichment for BPMs',
    formatter_class=argparse.ArgumentDefaultsHelpFormatter)
aa = parser.add_argument
aa('emap', type=str,
   metavar='EMAP_FILE', help='Location of the EMAP file.')
aa('bpm', type=str,
   metavar='BPM_FILE', help='Location of the BPM file.')
aa('enrichment', type=str,
   metavar='ENRICHMENT_FILE', help='Output file for GO enrichment.')
aa('-s', '--sort-go-by', dest='sort_go_by', type=str, default='p',
   choices=['p', 'accession', 'name', 'num_genes_with'],
   metavar='GO_SORT',
   help='The field to sort GO enrichment by. "p" is the p-value of the '
        'GO term. "accession" is the GO identifier, i.e., "GO:...". "name" '
        'is the GO short description, i.e., "histone exchange". And '
        '"num_genes_with" is the number of genes in the BPM module that are '
        'enriched with a particular GO term.')
aa('-t', '--order-go', dest='order_go', type=str, default='asc',
   choices=['asc', 'desc'], metavar='GO_ORDER',
   help='The order in which to sort GO enrichment. "asc" for ascending, '
        'and "desc" for descending.')
aa('-p', '--processes', dest='processes', type=int, default=__default_cpus,
   metavar='CPUs', help='The number of processes to run in parallel. If set to '
                        '1, the multiprocessing module will not be used. '
                        'You should also be nice to Funcassociate and not set '
                        'this too high.')
aa('--hide-enriched-genes', dest='hide_enriched_genes', action='store_true',
   help='If set, the enriched genes for each GO term will not be written '
        'to the output file. This may cut down on file size if there is '
        'a lot of enrichment.')
aa('--no-progress', dest='progress', action='store_false',
   help='If set, the progress bar will not be shown.')
aa('-v', '--verbose', dest='verbose', action='store_true',
   help='If set, more output will be shown.')

conf = parser.parse_args()

# Protect the user from themselves.
# If the provided number of processes is larger than the detected number of
# CPUs, forcefully lower it to the number of CPUs.
if conf.processes > __cpus:
    conf.processes = __cpus

# Set the global conf variable
bpm.conf = conf

