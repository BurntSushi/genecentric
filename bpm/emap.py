from collections import defaultdict
import csv

from bpm import conf, parallel

gis = defaultdict(float)
genes = set()
numgenes = 0

def load_genes():
    '''
    Loads all of the gene pairs and their corresponding interaction scores
    into memory. It also keeps a set of all genes for iterative purposes.

    This gene information is then available at the 'emap' module level, since
    they are both used pervasively throughout BPM generation.
    '''
    for row in csv.reader(open(conf.emap)):
        ginter = float(row[2])
        if ginter < 0:
            ginter = - (ginter ** 2)
        else:
            ginter = ginter ** 2
        gis[(row[0], row[1])] = ginter
        gis[(row[1], row[0])] = ginter

        genes.add(row[0])
        genes.add(row[1])

    parallel.inc_counter(parallel.costs['load_genes'])

def genecount():
    '''
    A simple method to fetch the total number of genes. It uses a pretty shotty
    memoization technique.

    Actually, I don't think it's even necessary. I think taking the length
    of a set is O(1) time complexity. Hmm...
    '''
    global numgenes

    if not numgenes:
        numgenes = len(genes)

    assert numgenes > 0, 'emap.load_genes must be called first'

    return numgenes

def gi(g1, g2):
    '''
    This indexing used to be a bit more complex, but the dict should contain
    both (g1, g2) and (g2, g1). It uses more memory but speeds up execution.
    '''
    return gis[(g1, g2)]

