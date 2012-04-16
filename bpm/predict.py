'''
The predict module is responsible for trying different prediction schemes
on sets of partitions.
'''

from bpm import conf, geneinter

def bpm_prediction(bpms):
    predictions = [] # [(gene1, gene2, # opp, # same, # nil, real score)]
    for g1, g2 in geneinter.test:
        same = opp = nopred = 0
        for A, B in bpms:
            if (g1 in A and g2 in A) or (g1 in B and g2 in B):
                same += 1
            elif (g1 in A and g2 in B) or (g1 in B and g2 in A):
                opp += 1
            else:
                nopred += 1
        score = geneinter.test[(g1, g2)]
        predictions.append((g1, g2, opp, same, nopred, score))

    return sorted(predictions,
                  key=lambda (g1, g2, o, s, n, r): (o > s, o, s, n),
                  reverse=True)

def negative_edges(parts):
    '''
    Traverse all 'test' edges, checks whether the endpoints are in opposing
    partitions R% of the time, and only reports those edges with a confidence
    score.
    '''
    predictions = [] # [(gene1, gene2, confidence, real score)]
    for g1, g2 in geneinter.test:
        opprat = ratio_opposite(parts, g1, g2)
        if opprat >= conf.R:
            predictions.append((g1, g2, opprat, geneinter.test[(g1, g2)]))
    return sorted(predictions, key=lambda (g1, g2, cf, real): cf, reverse=True)

def ratio_opposite(parts, g1, g2):
    '''
    The percentage of times that g1 and g2 are in opposing partitions.
    '''
    freq = 0
    for A, B in parts:
        if (g1 in A and g2 in B) or (g1 in B and g2 in A):
            freq += 1
    return float(freq) / float(len(parts))

