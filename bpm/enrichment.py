import re

from bpm import conf, faread, parallel

def enrich((bpmi, modi, genes)):
    goterms = faread.functionate(genes)

    parallel.inc_counter()
    parallel.print_progress()

    return bpmi, modi, genes, goterms

def sortgo(goterms):
    reverse = True if conf.order_go == 'desc' else False

    if conf.sort_go_by == 'p':
        return sorted(goterms, key=lambda acc: goterms[acc]['p'],
                      reverse=reverse)
    elif conf.sort_go_by == 'accession':
        return sorted(goterms, reverse=reverse)
    elif conf.sort_go_by == 'name':
        return sorted(goterms, key=lambda acc: goterms[acc]['name'].lower(),
                      reverse=reverse)
    elif conf.sort_go_by == 'num_genes_with':
        return sorted(goterms, key=lambda acc: goterms[acc]['num_with'],
                      reverse=reverse)

    assert False, 'Invalid sort by column.'

def read_bpm(bpmtext):
    lines = map(str.strip, bpmtext.splitlines())

    bpmids = lines[0]
    m = re.search('BPM(\d+)/Module(\d+)', bpmids)
    bpmi, modi = m.group(1), m.group(2)

    genes = set(map(str.strip, lines[1].split('\t')))

    goterms = {}
    gotermlns = lines[2:]
    for gotermln in gotermlns:
        columns = gotermln.split('\t')
        accession = columns[0].strip()
        p = columns[1].strip()
        num_with, num_query = map(int, columns[2].strip().split('/'))
        name = columns[3]

        if len(columns) == 5:
            egenes = set(columns[4].strip().split())
        else:
            egenes = set()

        assert len(egenes) == 0 or len(egenes) == num_with, columns

        goterms[accession] = {
            'p': float(p),
            'num_with': num_with,
            'num_query': num_query,
            'name': name,
            'genes': egenes,
        }

    return bpmi, modi, genes, goterms

def write_bpm(out, bpmi, modi, genes, goterms):
    obpm = ['> BPM%d/Module%d' % (bpmi, modi)]
    obpm.append('\t'.join(genes))

    for accession in sortgo(goterms):
        goterm = goterms[accession]

        if conf.hide_enriched_genes:
            egenes = ''
        else:
            egenes = '\t%s' % ' '.join(goterm['genes'])

        frac = '%d/%d' % (goterm['num_with'], goterm['num_query'])
        obpm.append('%s\t%f\t%s\t%s%s'
                     % (accession, goterm['p'], frac, goterm['name'], egenes))

    return '\n'.join(obpm)
