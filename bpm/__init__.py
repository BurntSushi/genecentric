import argparse
import multiprocessing as mp

try:
    __cpus = mp.cpu_count()
except NotImplementedError:
    __cpus = 1

parser = argparse.ArgumentParser(
    description='BPM generator',
    formatter_class=argparse.ArgumentDefaultsHelpFormatter)
aa = parser.add_argument
aa('emap', type=str,
   metavar='EMAP_FILE', help='Location of the EMAP file.')
aa('bpm', type=str,
   metavar='BPM_FILE', help='Where the BPM output will be written.')
aa('-c', '--gene-ratio', dest='C', type=float, default=0.90,
   metavar='RATIO', help='Gene ratio threshold')
aa('-j', '--jaccard', dest='jaccard', type=float, default=0.66,
   metavar='JACCARD_INDEX', help='Jaccard Index threshold')
aa('-m', '--num-bipartitions', dest='M', type=int, default=250,
   metavar='NUMBER_BIPARTITIONS', help='Number of bipartitions to generate')
aa('--minimum-size', dest='min_size', type=int, default=3,
   metavar='MIN_SIZE', 
   help='Minimum size of BPM. Smaller BPMs are pruned. '
        'Set to 0 to disable.')
aa('--maximum-size', dest='max_size', type=int, default=25,
   metavar='MAX_SIZE', 
   help='Maximum size of BPM. Bigger BPMs are pruned. '
        'Set to 0 to disable.')
aa('-p', '--processes', dest='processes', type=int, default=__cpus,
   metavar='CPUs', help='The number of processes to run in parallel. If set to '
                        '1, the multiprocessing module will not be used.')
aa('--no-prune', dest='pruning', action='store_false',
   help='If set, no pruning will occur. Note that --minimum-size and '
        '--maximum-size will still have an effect. Set those to 0 to '
        'disable that pruning.')
aa('-v', '--verbose', dest='verbose', action='store_true',
   help='If set, more output will be shown.')

conf = parser.parse_args()

# Protect the user from themselves.
# If the provided number of processes is larger than the detected number of
# CPUs, forcefully lower it to the number of CPUs.
if conf.processes > __cpus:
    conf.processes = __cpus

