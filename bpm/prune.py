from functools import partial
from itertools import combinations, product

from bpm import conf, emap, parallel

def prune(bpms):
    if conf.min_size > 0 or conf.max_size > 0:
        bpms = filter(lambda (A, B): satisfy_min_max(A, B), bpms)

    # If pruning is disabled, exit now.
    if not conf.pruning:
        return bpms

    # print 'Calculating interaction weights for all bpms...', 
    withI = parallel.pmap(interweight, bpms)
    withI = sorted(withI, key=lambda (iw, (A, B)): iw, reverse=True)
    # print 'Done.' 

    # print 'Pruning...', 
    pruned = []
    for iw, (A, B) in withI:
        jind = partial(jaccard_index, A.union(B))
        if all(map(lambda ji: ji < conf.jaccard,
                   [jind(S1.union(S2)) for S1, S2 in pruned])):
            pruned.append((A, B))
    # print 'Done.' 
    
    return pruned

def interweight((A, B)):
    gitup = lambda (g1, g2): emap.gi(g1, g2)

    def sum_within(S):
        return sum(map(gitup, combinations(S, 2)))

    within = sum_within(A) + sum_within(B)
    between = sum(map(gitup, product(A, B)))

    iweight = (within - between) / float(len(A) + len(B))

    return (iweight, (A, B))

def jaccard_index(A, B):
    return len(A.intersection(B)) / float(len(A.union(B)))

def constraint_min(A, B):
    return len(A) >= conf.min_size and len(B) >= conf.min_size

def constraint_max(A, B):
    return len(A) <= conf.max_size and len(B) <= conf.max_size

def satisfy_min_max(A, B):
    return ((conf.min_size == 0 or constraint_min(A, B))
            and
            (conf.max_size == 0 or constraint_max(A, B)))

